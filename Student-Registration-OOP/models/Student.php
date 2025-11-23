<?php

class Student
{
    private ?int $id;
    private string $name;
    private string $email;
    private int $age;

    public function __construct(string $name, string $email, int $age, ?int $id = null)
    {
        $this->name  = $name;
        $this->email = $email;
        $this->age   = $age;
        $this->id    = $id;
    }

    // --- Getters ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    // --- Optional Setters ---
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    // --- Convert object to array ---
    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'email' => $this->email,
            'age'   => $this->age,
        ];
    }

    // --- Useful for debugging ---
    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}
