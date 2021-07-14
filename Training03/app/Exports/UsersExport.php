<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class UsersExport implements FromView
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
        // echo '<pre>';
        // print_r(User::where('id', $this->id)->get());die();
        // echo '</pre>';
    //     return User::where('id', $this->id)->get();
    // }

    public function view(): View
    {
        return view('exports.users', [
            'users' => User::find($this->id)
        ]);
    }

}
