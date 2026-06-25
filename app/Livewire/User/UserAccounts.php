<?php

namespace App\Livewire\User;

use App\Models\User;
use Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use Livewire\WithPagination;

class UserAccounts extends Component
{
    use WithPagination;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = '';
    public string $dept = '';
    public $isSubmitting = false;
    
    public $deptEdit = '';
    public $departments;
    public $user, $usersID, $nameEdit, $emailEdit; // Storing Data in Edit Form
    public $roleEdit;
    public $search =""; // Search Function -> Model
    public $sortBy = "created_at"; // Sorts by creation
    public $sortByDirection = "ASC"; // Sorts direction on ascending by default
    public $perPage = 5; // Number of items per page
    public $editing = false; // Use for showing/hiding forms
    public $filterField = 'name'; // default filter

    public $showCreateModal = false;
    public $showEditModal = false;

    public function render()
    {
        return view('livewire.user.user-accounts',[ // Pass the variable to the view with Search and Sort
            'users' => User::search($this->search)
                ->select(
                        'users.*')
                ->when($this->search, function ($q) {
                    $q->where($this->filterField, 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sortBy, $this->sortByDirection)
                ->paginate($this->perPage)
        ]);
    }

    public function submit()
    {
        if ($this->isSubmitting) return; // prevent rapid resubmits
            $this->isSubmitting = true;

        try {
            $validated = $this->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'role' => 'required|in:Admin,User',
                'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
                'role' => ['required', 'string', 'in:User,Admin'],
            ]);

            $validated['password'] = Hash::make($validated['password']);
            session()->flash('success', 'User successfully added.'); // Flash Message for success(create)
            event(new Registered(($user = User::create($validated))));
            return redirect()->to(path: '/settings/user-accounts');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('submit-failed');
            throw $e;
        } finally {
            $this->isSubmitting = false;
        }

    }

    public function editData($id) // Pass the data(chosen ID) in the edit form
    {
        $users = User::findOrFail($id);
        $this->usersID = $users->id;
        $this->nameEdit = $users->name;
        $this->emailEdit = $users->email;
        $this->roleEdit = $users->role;
        $this->editing = true;
        $this->resetPage();

        $this->showEditModal = true;
    }

    public function editSubmit(){ // Edits the data of the chosen ID in the database
        
        try {
            $this->validate([
                'nameEdit' => 'required|string|max:255',
                'emailEdit' => 'required|string|email|max:255|unique:users,email,' . $this->usersID,
                'roleEdit' => 'required|in:Admin,User',
                // Add other validations as needed
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if (isset($e->validator->failed()['emailEdit']['Unique'])) {
                session()->flash('error', 'The email address is already taken.');
                return;
            }
            throw $e; // rethrow if it's a different validation error
        }

        $users = User::find($this->usersID);
        // Check if any data has changed
        $hasChanges = false;
        if (
            $users->name !== $this->nameEdit ||
            $users->email !== $this->emailEdit ||
            $users->role !== $this->roleEdit
        // Add more fields if needed
        ) {
            $hasChanges = true;
        }

        if (!$hasChanges) {
            session()->flash('warning', 'No changes were made to update.');
            return;
        }
        $users->name = $this->nameEdit;
        $users->email = $this->emailEdit;
        $users->role = $this->roleEdit;
        $users->save();
        $this->editing = false; // Hides Edit form if Edit Button is clicked

        session()->flash('success', 'User Information successfully updated.'); // Flash message for success(update)
        return redirect()->to(path: '/settings/user-accounts');
    }

    public function resetUserPassword($userID) // Reset User Password to "12345"
    {
        $user = User::findOrFail($userID);
        $user->password = Hash::make('12345');
        $user->save();
        session()->flash('warning', 'Password reset to 12345.'); // Flash Message for Reset
    }

    public function disableUser($id) // Disable Button if state == 1
    {
        $user = User::find($id);
            if ($user) {
                $user->state = 0; // Set state directly
                $user->save();
                session()->flash('error', 'The user account was disabled.'); // Flash Message for Disable
            $this->resetPage();
        }
    }
    public function enableUser($id) // Enable Button if state == 1
    {
        $user = User::find($id);
            if ($user) {
                $user->state = 1; // Set state directly
                $user->save();
                session()->flash('success', 'The user account was enabled.'); // Flash Message for Disable
            $this->resetPage();
        }
    }

    public function changePerPage($number) // Changers page per Input
    {
        $this->perPage = $number;
        $this->resetPage();
    }

    public function setSortBy($sortByField){ // Sorts direction by descending order

        if($this->sortBy === $sortByField){
            $this->sortByDirection = ($this->sortByDirection == "DESC") ? 'ASC' : "DESC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortByDirection = 'ASC';
    }
}
