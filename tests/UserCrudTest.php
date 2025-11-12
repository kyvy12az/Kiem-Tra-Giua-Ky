<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

class UserCrudTest {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function testAddUser() {
        $userData = [
            'name' => 'Test User',
            'email' => 'testuser@example.com'
        ];
        $result = addUser($userData);
        assert($result === true, 'Failed to add user');
    }

    public function testEditUser() {
        $userId = 1; // Assuming a user with ID 1 exists
        $updatedData = [
            'name' => 'Updated User',
            'email' => 'updateduser@example.com'
        ];
        $result = editUser($userId, $updatedData);
        assert($result === true, 'Failed to edit user');
    }

    public function testDeleteUser() {
        $userId = 1; // Assuming a user with ID 1 exists
        $result = deleteUser($userId);
        assert($result === true, 'Failed to delete user');
    }

    public function testViewUser() {
        $userId = 1; // Assuming a user with ID 1 exists
        $user = viewUser($userId);
        assert($user !== null, 'Failed to retrieve user');
    }

    public function runTests() {
        $this->testAddUser();
        $this->testEditUser();
        $this->testDeleteUser();
        $this->testViewUser();
        echo "All tests passed!";
    }
}

$test = new UserCrudTest();
$test->runTests();
?>