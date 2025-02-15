<?php
namespace App\Http\Contracts;

use Illuminate\Http\Request;

interface RepositoryContract
{
    public function gets();
    public function get(string $id);
    public function store(Request $request);
    public function update(Request $request, string $id);
    public function destroy(string $id);
}
