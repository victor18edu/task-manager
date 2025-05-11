<input type="hidden" name="id" id="taskId">
<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" class="form-control" id="title" name="title" required>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descrição</label>
    <textarea class="form-control" id="description" name="description"></textarea>
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select class="form-select" id="status" name="status" required>
        <option value="pending">Pendente</option>
        <option value="completed">Concluída</option>
    </select>
</div>

<div class="mb-3" id="usersFields">
    <label for="user_ids" class="form-label">Usuários vinculados</label>
    <select class="form-select" id="user_ids" name="user_ids[]" multiple></select>
</div>
