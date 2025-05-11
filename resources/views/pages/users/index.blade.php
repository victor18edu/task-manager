<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.bootstrap5.css">
    @endpush
    <x-slot name="header">
        <div class="d-flex justify-content-between align-middle">
            <h4 class="font-semibold">
                {{ __('Usuários') }}
            </h4>

            <div class="flex justify-end">
                <button class="btn btn-primary" id="btnCreateUser">Criar Usuário</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="card shadow-sm rounded-3">
            <div class="card-body">
                <table id="users-table" class="table table-striped table-hover">
                    <thead>
                        <tr class="py-2">
                            <th class="">Nome</th>
                            <th class="">Email</th>
                            <th class="">Status</th>
                            <th class="" width="15%">Ações</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    @include('pages.users.modal')
    @push('scripts')
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/2.3.0/js/dataTables.bootstrap5.js"></script>
        <script>
            $(document).ready(function() {

                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                const $form = $('#userForm');

                // Inicializar o DataTable
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('users.datatable') }}',
                    columns: [{
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
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

                // Abrir modal para criação
                $('#btnCreateUser').on('click', function() {
                    $form.trigger('reset');
                    $('#userModalLabel').text('Novo Usuário');
                    $('#userId').val('');
                    $('#passwordFields').show();
                    modal.show();
                });

                // Abrir modal para edição
                $(document).on('click', '.btn-edit-user', function() {
                    const id = $(this).data('id');
                    $.get(`/users/${id}/edit`, function(res) {
                        $('#userModalLabel').text('Editar Usuário');
                        $('#userId').val(res.data.id);
                        $('#name').val(res.data.name);
                        $('#email').val(res.data.email);
                        $('#status').val(res.data.status);
                        $('#password').val('');
                        modal.show();
                    });
                });

                // Submissão do formulário (criação/edição)
                $form.on('submit', function(e) {
                    e.preventDefault();
                    const id = $('#userId').val();
                    const url = id ? `/users/${id}` : '/users';
                    const method = id ? 'PUT' : 'POST';
                    const formData = $form.serialize();

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData + '&_method=' + method + '&_token={{ csrf_token() }}',
                        success: res => {
                            Swal.fire('Sucesso', res.message, 'success');
                            $('#users-table').DataTable().ajax.reload();
                            modal.hide();
                        },
                        error: xhr => {
                            let message = 'Erro ao salvar.';
                            if (xhr.responseJSON?.errors) {
                                message = Object.values(xhr.responseJSON.errors)
                                    .flat()
                                    .join('<br>');
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

            });

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
                                $('#users-table').DataTable().ajax.reload();
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
