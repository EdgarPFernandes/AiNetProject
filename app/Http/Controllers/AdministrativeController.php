<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\AdministrativeFormRequest;

class AdministrativeController extends Controller
{
    use \App\Traits\UserPhotoFileStorage;

    public function index(Request $request): View
    {
        $administrativesQuery = User::where('type', 'A')
            ->orderBy('name');
        $filterByName = $request->query('name');
        if ($filterByName) {
            $administrativesQuery->where('name', 'like', "%$filterByName%");
        }
        $administratives = $administrativesQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'administratives.index',
            compact('administratives', 'filterByName')
        );
    }

    public function create(): View
    {
        $newAdministrative = new User();
        $newAdministrative->type = 'A';
        return view('administratives.create')
            ->with('administrative', $newAdministrative);
    }

    public function store(AdministrativeFormRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $newAdministrative = new User();
        $newAdministrative->type = 'A';
        $newAdministrative->name = $validatedData['name'];
        $newAdministrative->email = $validatedData['email'];
        $newAdministrative->admin = $validatedData['admin'];
        $newAdministrative->gender = $validatedData['gender'];
        // Initial password is always 123
        $newAdministrative->password = bcrypt('123');
        $newAdministrative->save();
        // File store is the last thing to execute!
        // Files do not rollback, so the probability of having a pending file 
        // (not referenced by any user) is reduced by being the last operation
        $this->storeUserPhoto($request->photo_file, $newAdministrative);        
        $url = route('administratives.show', ['administrative' => $newAdministrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$newAdministrative->name}</u></a> has been created successfully!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function edit(User $administrative): View
    {
        return view('administratives.edit')
            ->with('administrative', $administrative);
    }

    public function update(AdministrativeFormRequest $request, User $administrative): RedirectResponse
    {
        $validatedData = $request->validated();
        $administrative->type = 'A';
        $administrative->name = $validatedData['name'];
        $administrative->email = $validatedData['email'];
        $administrative->admin = $validatedData['admin'];
        $administrative->gender = $validatedData['gender'];
        $administrative->save();
        if ($request->photo_file) {
            $this->deleteUserPhoto($administrative);
            $this->storeUserPhoto($request->photo_file, $administrative);
        }
        $url = route('administratives.show', ['administrative' => $administrative]);
        $htmlMessage = "Administrative <a href='$url'><u>{$administrative->name}</u></a> has been updated successfully!";
        return redirect()->route('administratives.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(User $administrative): RedirectResponse
    {
        try {
            $url = route('administratives.show', ['administrative' => $administrative]);
            $fileName = $administrative->photo_url;
            $administrative->delete();
            $this->deletePhotoFile($fileName);
            $alertType = 'success';
            $alertMsg = "Administrative {$administrative->name} has been deleted successfully!";
        } catch (\Exception $error) {
            $alertType = 'danger';
            $alertMsg = "It was not possible to delete the administrative
                            <a href='$url'><u>{$administrative->name}</u></a>
                            because there was an error with the operation!";
        }
        return redirect()->route('administratives.index')
            ->with('alert-type', $alertType)
            ->with('alert-msg', $alertMsg);
    }

    public function destroyPhoto(User $administrative): RedirectResponse
    {
        if ($this->deleteUserPhoto($administrative)) {
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of administrative {$administrative->name} has been deleted.");
        } else {
            return redirect()->back()
                ->with('alert-type', 'warning')
                ->with('alert-msg', "Photo of administrative {$administrative->name} does not exist.");
        }
    }    

    public function show(User $administrative): View
    {
        return view('administratives.show')->with('administrative', $administrative);
    }

}
