<?php

namespace Admin;

require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Role.php';

class UserController extends \Controller
{
  public function index(): void
  {
    $this->requireRole('admin');
    $users = (new \User())->allWithRoles();
    $this->render('admin.users', ['users' => $users]);
  }

  public function create(): void
  {
    $this->requireRole('admin');
    $roles = (new \Role())->findAll();
    $this->render('admin.user_form', [
      'roles' => $roles,
      'user' => null
    ]);
  }

  public function store(): void
  {
    $this->requireRole('admin');

    (new \User())->create(
      $_POST['nome'],
      $_POST['email'],
      $_POST['senha'],
      (int) $_POST['role_id']
    );

    $this->redirect('/admin/usuarios');
  }

  public function edit(string $id): void
  {

    $this->requireRole('admin');

    $user = (new \User())->findById((int) $id);
    $roles = (new \Role())->findAll();

    $this->render('admin.user_form', [
      'user' => $user,
      'roles' => $roles
    ]);
  }

  public function update(string $id): void
  {

    $this->requireRole('admin');
    (new \User())->update(
      (int) $id,
      $_POST['nome'],
      $_POST['email'],
      (int) $_POST['role_id']
    );

    $this->redirect('/admin/usuarios');
  }

  public function destroy(string $id): void
  {

    $this->requireRole('admin');

    (new \User())->delete((int) $id);
    $this->redirect('/admin/usuarios');
  }
}
