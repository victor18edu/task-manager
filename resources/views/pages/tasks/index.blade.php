<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    @endpush

    <x-slot name="header">
        <div class="d-flex justify-content-between align-middle">
            <h4 class="font-semibold">
                {{ __('Tarefas') }}
            </h4>

            <div class="flex justify-end">
                <button class="btn btn-primary" id="btnCreateTask">Criar Tarefa</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <div class="mb-5 row">
                    <div class="col-3">
                        <input type="text" id="filterTitle" class="form-control" placeholder="Buscar por título">
                    </div>
                    <div class="col-3">
                        <select id="filterStatus" class="form-select">
                            <option value="">Todos os status</option>
                            <option value="pending">Pendente</option>
                            <option value="completed">Concluída</option>
                        </select>

                    </div>
                    <div class="col-3">
                        <select id="filterUser" class="form-select">
                        </select>
                    </div>
                    <div class="col-3 d-flex justify-content-end">
                        <button id="btnFilter" class="btn btn-outline-secondary">Filtrar</button>
                    </div>
                </div>

                <table id="tasks-table" class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Status</th>
                            <th width="20%">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('pages.tasks.modal')

    @push('scripts')
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.full.min.js"></script>

        <script>
            $(document).ready(function() {
                const modal = new bootstrap.Modal(document.getElementById('taskModal'));
                const $form = $('#taskForm');

                const table = $('#tasks-table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: '{{ route('tasks.datatable') }}',
                        data: function(d) {
                            d.title = $('#filterTitle').val();
                            d.status = $('#filterStatus').val();
                            d.user_id = $('#filterUser').val();
                        }
                    },
                    columns: [{
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'description',
                            name: 'description'
                        },
                        {
                            data: 'status_label',
                            name: 'status'
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
                    },
                    responsive: true
                });

                $('#btnFilter').click(() => table.ajax.reload());

                $('#btnCreateTask').on('click', function() {
                    $form.trigger('reset');
                    $('#taskModalLabel').text('Nova Tarefa');
                    $('#taskId').val('');
                    $('#user_ids').val(null).trigger('change');
                    $('#usersFields').hide();
                    modal.show();
                });

                $(document).on('click', '.mark-complete', function() {
                    const taskId = $(this).data('id');

                    $.ajax({
                        url: `/tasks/${taskId}/complete`,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(res) {
                            Swal.fire('Sucesso', res.message, 'success');
                            $('#tasks-table').DataTable().ajax.reload(null, false);
                        },
                        error: function() {
                            Swal.fire('Erro', 'Não foi possível concluir a tarefa.', 'error');
                        }
                    });
                });

                // Editar
                $(document).on('click', '.btn-edit-task', function() {
                    const id = $(this).data('id');
                    $.get(`/tasks/${id}/edit`, function(res) {
                        $('#taskModalLabel').text('Editar Tarefa');
                        $('#taskId').val(res.data.id);
                        $('#title').val(res.data.title);
                        $('#description').val(res.data.description);
                        $('#status').val(res.data.status);
                        $('#usersFields').show();
                        $('#user_ids').val(res.data.users.map(u => u.id)).trigger('change');
                        modal.show();
                    });
                });

                // Submeter formulário
                $form.on('submit', function(e) {
                    e.preventDefault();
                    const id = $('#taskId').val();
                    const url = id ? `/tasks/${id}` : '/tasks';
                    const method = id ? 'PUT' : 'POST';
                    const formData = $form.serialize();

                    $.ajax({
                        url,
                        type: 'POST',
                        data: formData + '&_method=' + method + '&_token={{ csrf_token() }}',
                        success: res => {
                            Swal.fire('Sucesso', res.message, 'success');
                            $('#tasks-table').DataTable().ajax.reload();
                            modal.hide();
                        },
                        error: xhr => {
                            let message = 'Erro ao salvar.';
                            if (xhr.responseJSON?.errors) {
                                message = Object.values(xhr.responseJSON.errors).flat().join(
                                    '<br>');
                            } else if (xhr.responseJSON?.message) {
                                message = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                title: 'Erro',
                                html: message,
                                icon: 'error'
                            });
                        }
                    });
                });

                $.get('/tasks/users', function(data) {
                    $('#user_ids').empty().select2({
                        theme: "bootstrap-5",
                        data: data.map(u => ({
                            id: u.id,
                            text: u.name
                        })),
                        dropdownParent: $('#taskModal'),
                        width: '100%',
                        placeholder: "Selecione usuários",
                        allowClear: true
                    });

                    $('#filterUser').empty().select2({
                        theme: "bootstrap-5",
                        data: data.map(u => ({
                            id: u.id,
                            text: u.name
                        })),
                        width: '100%',
                        placeholder: "Selecione usuários",
                        allowClear: true
                    }).val('{{ auth()->id() }}').trigger('change');;
                });
            });

            // Excluir tarefa
            function confirmDelete(url) {
                Swal.fire({
                    title: 'Tem certeza?',
                    text: "Essa ação não poderá ser desfeita!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        const token = $('meta[name="csrf-token"]').attr('content');
                        $.ajax({
                            url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: token
                            },
                            success: res => {
                                Swal.fire('Sucesso', res.message, 'success');
                                $('#tasks-table').DataTable().ajax.reload();
                            },
                            error: xhr => {
                                const msg = xhr.responseJSON?.message || 'Erro ao excluir.';
                                Swal.fire('Erro', msg, 'error');
                            }
                        });
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>
