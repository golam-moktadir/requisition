<?php declare(strict_types=1);

namespace Modules\IncomeExpense\Services;

use Illuminate\Database\Eloquent\Collection; 
use Illuminate\Support\Facades\Auth;
use Modules\IncomeExpense\Repositories\DailyIncomeExpenseRepository;
use Modules\IncomeExpense\Models\DailyIncomeExpense AS TableModel;

class DailyIncomeExpenseService
{
    protected $repository;

    public function __construct(DailyIncomeExpenseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getById($id): ?TableModel
    {
        return $this->repository->getById($id);
    }

    public function create(array $data): TableModel
    {
        $data['created_by'] = Auth::id();
        return $this->repository->create($data);
    }

    public function update($id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id): bool
    {
        return $this->repository->delete($id);
    }
}
