<?php

namespace App\Livewire\Employer\Company;

use App\Models\Company;
use Illuminate\View\View;
use Livewire\Component;

class CompanyIndex extends Component
{
    public string $name = '';

    public string $description = '';

    public string $search = '';

    /**
     * @return array<string, array<int, string>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function submit(): void
    {
        $this->authorize('create', Company::class);

        $data = $this->validate();

        auth()->user()->companies()->create([
            ...$data,
            'owner_id' => auth()->id(),
        ]);

        session()->flash('status', 'Company created successfully.');

        $this->reset(['name', 'description']);
    }

    public function render(): View
    {
        $companies = auth()->user()->companies()
            ->when($this->search !== '', function ($query): void {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->withCount(['departments', 'employees', 'invitations'])
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('livewire.employer.company.company-index', [
            'companies' => $companies,
        ])->layout('layouts.dashboard');
    }
}
