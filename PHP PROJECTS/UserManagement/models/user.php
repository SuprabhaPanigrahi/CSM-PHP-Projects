<?php

class User
{
    private $fullname;
    private $email;
    private $password;
    private $phone;
    private $dob;
    private $gender;
    private $qualification;
    private $address;
    private $country;
    private $zip;
    private $photo;

    public function __construct ($fullname='', $email='', $password='', $phone='', $dob='', $gender='', $qualification='', $address='', $country='', $zip='', $photo='')
    {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;

        $this->phone = $phone;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->qualification = $qualification;
        $this->address = $address;
        $this->country = $country;
        $this->zip = $zip;
        $this->photo = $photo;
    }


    public function getFullname()
    {
        return $this->fullname;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    
    public function getPhone()
    {
        return $this->phone;
    }

    public function getDob()
    {
        return $this->dob;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getQualification()
    {
        return $this->qualification;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getZip()
    {
        return $this->zip;
    }

    public function getPhoto()
    {
        return $this->photo;
    }


    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function setDob($dob)
    {
        $this->dob = $dob;
    }
    public function setGender($gender)
    {
        $this->gender = $gender;
    }
    public function setQualification($qualification)
    {
        $this->qualification = $qualification;
    }
    public function setAddress($address)
    {
        $this->address = $address;
    }
    public function setCountry($country)
    {
        $this->country = $country;
    }
    public function setZip($zip)
    {
        $this->zip = $zip;
    }
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }



    public function  __toString()
    {
        return "User Details:<br>" .
            "Full Name: " . $this->fullname . "<br>" .
            "Email: " . $this->email . "<br>" .
            "Phone: " . $this->phone . "<br>" .
            "Date of Birth: " . $this->dob . "<br>" .
            "Gender: " . $this->gender . "<br>" .
            "Qualification: " . $this->qualification . "<br>" .
            "Address: " . $this->address . "<br>" .
            "Country: " . $this->country . "<br>" .
            "ZIP: " . $this->zip . "<br>";
    }
}
?>