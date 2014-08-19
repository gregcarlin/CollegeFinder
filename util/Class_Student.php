<?php

require_once "Class_StoredObject.php";

class Student extends StoredObject {
  function id() {
    return $this->get("id");
  }

  function firstName() {
    return $this->get("fname");
  }

  function lastName() {
    return $this->get("lname");
  }

  function fullName() {
    return StoredObject::isKnown($this->firstName(), $this->lastName()) ? ($this->firstName() . ' ' . $this->lastName()) : StoredObject::$UNKNOWN;
  }

  function sat() {
    return $this->get("sat");
  }

  function hasSat() {
    return StoredObject::isKnown($this->sat());
  }

  function satMath() {
    return $this->get("sat_mt");
  }

  function hasSatMath() {
    return StoredObject::isKnown($this->satMath());
  }

  function satReading() {
    return $this->get("sat_cr");
  }

  function hasSatReading() {
    return StoredObject::isKnown($this->satReading());
  }

  function satWriting() {
    return $this->get("sat_wr");
  }

  function hasSatWriting() {
    return StoredObject::isKnown($this->satWriting());
  }

  function hasSATSubscores() {
    return StoredObject::isKnown($this->satMath(), $this->satReading(), $this->satWriting());
  }

  function act() {
    return $this->get("act");
  }

  function hasAct() {
    return StoredObject::isKnown($this->act());
  }

  function actEnglish() {
    return $this->get("act_en");
  }

  function hasActEnglish() {
    return StoredObject::isKnown($this->actEnglish());
  }

  function actMath() {
    return $this->get("act_mt");
  }

  function hasActMath() {
    return StoredObject::isKnown($this->actMath());
  }

  function actReading() {
    return $this->get("act_rd");
  }

  function hasActReading() {
    return StoredObject::isKnown($this->actReading());
  }

  function actScience() {
    return $this->get("act_sc");
  }

  function hasActScience() {
    return StoredObject::isKnown($this->actScience());
  }

  function actWriting() {
    return $this->get("act_wr");
  }

  function hasActWriting() {
    return StoredObject::isKnown($this->actWriting());
  }

  function weightedGPA() {
    return $this->get("gpa_weight");
  }

  function unweightedGPA() {
    return $this->get("gpa_noweight");
  }
}

?>