<?php

declare(strict_types=1);

namespace Lifhold\Users\Eloquent\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Lifhold\Users\Contracts\User;
use Lifhold\Users\Contracts\UsersService;
use Lifhold\Users\Events\CreatedUserEvent;
use Lifhold\Users\Events\DeletedUserEvent;
use Lifhold\Users\Exceptions\UserModuleException;
use Lifhold\Users\Exceptions\UserNotFoundException;

class UsersEloquentService implements UsersService
{

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function getOne(int $id): User
    {
        try {
            return \Lifhold\Users\Eloquent\Models\User::findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFoundException(
                $exception->getMessage(),
                intval($exception->getCode()),
                $exception
            );
        }
    }

    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     * @throws UserModuleException
     */
    public function findByEmail(string $email): User
    {
        try {
            return \Lifhold\Users\Eloquent\Models\User::where([
                'email' => filter_var($email, FILTER_SANITIZE_EMAIL)
            ])->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            throw new UserNotFoundException(
                $exception->getMessage(),
                intval($exception->getCode()),
                $exception
            );
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @return User
     * @throws UserModuleException
     */
    public function create(string $email, string $password): User
    {
        try {
            $user = new \Lifhold\Users\Eloquent\Models\User();
            $user->fill([
                'email' => $email,
                'password' => Hash::make($password)
            ]);
            $user->saveOrFail();
            Event::dispatch(new CreatedUserEvent($user));
            return $user;
        } catch (\Exception $exception) {
            throw new UserNotFoundException(
                $exception->getMessage(),
                intval($exception->getCode()),
                $exception
            );
        }

    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $record = \Lifhold\Users\Eloquent\Models\User::query()
            ->where('id', '=', $id)
            ->first();

        if (is_null($record)) {
            return false;
        }
        try {
            $result = $record->delete();
            Event::dispatch(new DeletedUserEvent($record));
            return boolval($result);
        } catch (\Exception $exception) {
            return false;
        }
    }


}
