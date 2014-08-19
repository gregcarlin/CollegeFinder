<?php

require_once "Class_StoredObject.php";

class School extends StoredObject {
  function id() {
    return $this->get("id");
  }

  function name() {
    return $this->get("name");
  }

  function address() {
    return $this->getTagged("address", "Address");
  }

  function city() {
    return $this->get("city");
  }

  function state() {
    return $this->get("state");
  }

  function zip() {
    return $this->get("zip");
  }

  function website() {
    return $this->getURL("website", "Main Website");
  }

  function county() {
    return $this->get("county");
  }

  function longitude() {
    return $this->get("longitude");
  }

  function latitude() {
    return $this->get("latitude");
  }

  function phone() {
    return $this->getPhone("phone");
  }

  function admissionsURL() {
    return $this->getURL("admis_url", "Admissions");
  }

  function financialAidURL() {
    return $this->getURL("finance_url", "Financial Aid");
  }

  function applicationURL() {
    return $this->getURL("app_url", "Online Application");
  }

  function netPriceURL() {
    return $this->getURL("net_price_url", "Net Price Calculator");
  }

  private static $sectors = array(0 => "Administrative", 1 => "Public and 4+ Years", 2 => "Private, Non-Profit and 4+ Years", 3 => "Private, For-Profit, and 4+ Years", 4 => "Public and 2 Years", 5 => "Private, Non-Profit, and 2 Years", 6 => "Private, For-Profit, and 2 Years", 7 => "Public and Less Than 2 Years", 8 => "Private, Non-Profit, and Less Than 2 Years", 9 => "Private, For-Profit, and Less Than 2 Years", 99 => "Unknown");
  function sector() {
    return School::$sectors[$this->get("sector")];
  }

  private static $levels = array(1 => "4+ Years", 2 => "At least 2 but less than 4 years", 3 => "Less than 2 years", -3 => "Unknown Level");
  function level() {
    return School::$levels[$this->get("level")];
  }

  private static $controls = array(1 => "Public", 2 => "Private (Non-Profit)", 3 => "Private (For-Profit)", -3 => "Unknown Control");
  function control() {
    return School::$controls[$this->get("control")];
  }

  // TODO max offering, under offering, grad offering

  private static $maxDegs = array(11 => "Doctor's - Research/Scholarship and Professional Practice", 12 => "Doctor's - Research/Scholarship", 13 => "Doctor's - Professional Practice", 14 => "Doctor's", 20 => "Master's", 30 => "Bachelor's", 40 => "Associate's", 0 => "None", -3 => "Unknown");
  function maxDegree() {
    return School::$maxDegs[$this->get("max_degree")];
  }

  private static $hasDegs = array(1 => "Yes", 2 => "No", -3 => "Not Available");
  function grantsDegrees() {
    return School::$hasDegs[$this->get("grants_degrees")];
  }

  function historicallyBlack() {
    return $this->get("historically_black");
  }

  private static $hospital = array(1 => "Has a Hospital", 2 => "Does not have a Hospital", -1 => "Hospital Presence Not Reported", -2 => "Hosptial Presence Not Applicable");
  function hasHospital() {
    return School::$hospital[$this->get("has_hospital")];
  }

  private static $medDeg = array(1 => "Yes", 2 => "No", -1 => "Not Reported", -2 => "Not Applicable");
  function grantsMedicalDegree() {
    return School::$medDeg[$this->get("grants_med_deg")];
  }

  function tribal() {
    return $this->get("tribal");
  }

  private static $urban = array(11 => "Large City", 12 => "Midsize City", 13 => "Small City", 21 => "Large Suburb", 22 => "Midsize Suburb", 23 => "Small Suburb", 31 => "Fringe Town", 32 => "Distant Town", 33 => "Remote Town", 41 => "Fringe Rural", 42 => "Distant Rural", 43 => "Remote Rural", -3 => "Setting Unknown");
  function urbanization() {
    return School::$urban[$this->get("urbanization")];
  }

  function openToPublic() {
    return $this->get("open_to_public");
  }

  // TODO put in full statuses if anyone actually cares
  function status() {
    return $this->get("status");
  }

  // TODO put in full regions if anyone actually cares
  function region() {
    return $this->get("region");
  }

  function closed() {
    return $this->has("closed") ? ("Closed on " . $this->get("closed")) : ("Currently Open");
  }

  // TODO active, primarily_postsecondary, postsecondary, postsecondary_and_title, reporting_method

  // TODO split?
  function alias() {
    return $this->get("alias");
  }

  // TODO category and carnegies

  function landGrant() {
    return $this->get("land_grant");
  }

  // TODO

  function fax() {
    return $this->getPhone("fax");
  }

  function congressionalDistrict() {
    return $this->get("congress_district");
  }

  // start supplementary information

  function maleApplicants() {
    return $this->get("applied_m");
  }

  function femaleApplicants() {
    return $this->get("applied_f");
  }

  function admittedMales() {
    return $this->get("admit_m");
  }

  function admittedFemales() {
    return $this->get("admit_f");
  }

  function fullTimeEnrolledMales() {
    return $this->get("enroll_full_m");
  }

  function fullTimeEnrolledFemales() {
    return $this->get("enroll_full_f");
  }

  function partTimeEnrolledMales() {
    return $this->get("enroll_part_m");
  }

  function partTimeEnrolledFemales() {
    return $this->get("enroll_part_f");
  }

  function numberSubmittingSAT() {
    return $this->get("sat_num");
  }

  function rawProportionSubmittingSAT() {
    return $this->get("sat_prop");
  }

  function proportionSubmittingSAT() {
    return StoredObject::isKnown($this->rawProportionSubmittingSAT()) ? (round($this->rawProportionSubmittingSAT() * 100) . '%') : StoredObject::$UNKNOWN;
  }

  function numberSubmittingACT() {
    return $this->get("act_num");
  }

  function rawProportionSubmittingACT() {
    return $this->get("act_prop");
  }

  function proportionSubmittingACT() {
    return StoredObject::isKnown($this->rawProportionSubmittingACT()) ? (round($this->rawProportionSubmittingACT() * 100) . '%') : StoredObject::$UNKNOWN;
  }

  function satReading25() {
    return $this->get("sat_cr_25");
  }

  function satReading75() {
    return $this->get("sat_cr_75");
  }

  function hasSatReading() {
    return StoredObject::isKnown($this->satReading25(), $this->satReading75());
  }

  function satReading50() {
    return StoredObject::getAvg($this->satReading25(), $this->satReading75());
  }

  function satReadingRange() {
    return StoredObject::formatRange($this->satReading25(), $this->satReading75());
  }

  function satMath25() {
    return $this->get("sat_mt_25");
  }

  function satMath75() {
    return $this->get("sat_mt_75");
  }

  function hasSatMath() {
    return StoredObject::isKnown($this->satMath25(), $this->satMath75());
  }

  function satMath50() {
    return StoredObject::getAvg($this->satMath25(), $this->satMath75());
  }

  function satMathRange() {
    return StoredObject::formatRange($this->satMath25(), $this->satMath75());
  }

  function satWriting25() {
    return $this->get("sat_wr_25");
  }

  function satWriting75() {
    return $this->get("sat_wr_75");
  }

  function hasSatWriting() {
    return StoredObject::isKnown($this->satWriting25(), $this->satWriting75());
  }

  function satWriting50() {
    return StoredObject::getAvg($this->satWriting25(), $this->satWriting75());
  }

  function satWritingRange() {
    return StoredObject::formatRange($this->satWriting25(), $this->satWriting75());
  }

  function sat25() {
    return StoredObject::isKnown($this->satReading25(), $this->satMath25(), $this->satWriting25()) ? ($this->satReading25() + $this->satMath25() + $this->satWriting25()) : StoredObject::$UNKNOWN;
  }

  function hasSat25() {
    return StoredObject::isKnown($this->sat25());
  }

  function sat75() {
    return StoredObject::isKnown($this->satReading75(), $this->satMath75(), $this->satWriting75()) ? ($this->satReading75() + $this->satMath75() + $this->satWriting75()) : StoredObject::$UNKNOWN;
  }

  function hasSat75() {
    return StoredObject::isKnown($this->sat75());
  }

  function sat50() {
    return StoredObject::getAvg($this->sat25(), $this->sat75());
  }

  function satRange() {
    return StoredObject::formatRange($this->sat25(), $this->sat75());
  }

  function actEnglish25() {
    return $this->get("act_en_25");
  }

  function actEnglish75() {
    return $this->get("act_en_75");
  }

  function actEnglish50() {
    return StoredObject::getAvg($this->actEnglish25(), $this->actEnglish75());
  }

  function hasActEnglish() {
    return StoredObject::isKnown($this->actEnglish25(), $this->actEnglish75());
  }

  function actEnglishRange() {
    return StoredObject::formatRange($this->actEnglish25(), $this->actEnglish75());
  }

  function actMath25() {
    return $this->get("act_mt_25");
  }

  function actMath75() {
    return $this->get("act_mt_75");
  }

  function actMath50() {
    return StoredObject::getAvg($this->actMath25(), $this->actMath75());
  }

  function hasActMath() {
    return StoredObject::isKnown($this->actMath25(), $this->actMath75());
  }

  function actMathRange() {
    return StoredObject::formatRange($this->actMath25(), $this->actMath75());
  }

  function actWriting25() {
    return $this->get("act_wr_25");
  }

  function actWriting75() {
    return $this->get("act_wr_75");
  }

  function actWriting50() {
    return StoredObject::getAvg($this->actWriting25(), $this->actWriting75());
  }

  function hasActWriting() {
    return StoredObject::isKnown($this->actWriting25(), $this->actWriting75());
  }

  function actWritingRange() {
    return StoredObject::formatRange($this->actWriting25(), $this->actWriting75());
  }

  function act25() {
    if($this->has("act_cm_25")) {
      return $this->get("act_cm_25");
    } else if(StoredObject::isKnown($this->actEnglish25(), $this->actMath25(), $this->actWriting25())) {
      return ($this->actEnglish25() + $this->actMath25() + $this->actWriting25()) / 3;
    } else {
      return StoredObject::$UNKNOWN;
    }
  }

  function act75() {
    if($this->has("act_cm_75")) {
      return $this->get("act_cm_75");
    } else if(StoredObject::isKnown($this->actEnglish75(), $this->actMath75(), $this->actWriting75())) {
      return ($this->actEnglish75() + $this->actMath75() + $this->actWriting75()) / 3;
    } else {
      return StoredObject::$UNKNOWN;
    }
  }

  function hasAct25() {
    return StoredObject::isKnown($this->act25());
  }

  function hasAct75() {
    return StoredObject::isKnown($this->act75());
  }

  function actRange() {
    return StoredObject::formatRange($this->act25(), $this->act75());
  }

  function undergraduateApplicationFee() {
    return $this->getMoney("app_fee_u");
  }

  function graduateApplicationFee() {
    return $this->getMoney("app_fee_g");
  }

  function dormCapacity() {
    return $this->get("room_cap");
  }

  function mealsPerWeek() {
    return $this->get("meals_wk");
  }

  function roomCost() {
    return $this->getMoney("room_cost");
  }

  function boardCost() {
    return $this->getMoney("board_cost");
  }

  function totalCost() {
    return $this->getMoneyWithBackup("total_cost", $this->roomCost(), $this->boardCost());
  }

  function enrolledMales() {
    return $this->getWithBackup("enroll_m", $this->partTimeEnrolledMales(), $this->fullTimeEnrolledMales());
  }

  function enrolledFemales() {
    return $this->getWithBackup("enroll_f", $this->partTimeEnrolledFemales(), $this->fullTimeEnrolledFemales());
  }

  function enrolled() {
    return $this->getWithBackup("enroll", $this->enrolledMales(), $this->enrolledFemales(), $this->partTimeEnrolled(), $this->fullTimeEnrolled());
  }

  function rawProportionMale() {
    return StoredObject::getFrac($this->enrolledMales(), $this->enrolled());
  }

  function proportionMale() {
    return StoredObject::formatFrac($this->rawProportionMale());
  }

  function applicants() {
    return $this->getWithBackup("applied", $this->maleApplicants(), $this->femaleApplicants());
  }

  function admitted() {
    return $this->getWithBackup("admitted", $this->admittedMales(), $this->admittedFemales());
  }

  function denied() {
    return StoredObject::isKnown($this->applicants(), $this->admitted()) ? ($this->applicants() - $this->admitted()) : -1;
  }

  function rawAcceptanceRate() {
    return StoredObject::getFrac($this->admitted(), $this->applicants());
  }

  function acceptanceRate() {
    return StoredObject::formatFrac($this->rawAcceptanceRate());
  }

  function rawMaleAcceptanceRate() {
    return StoredObject::getFrac($this->admittedMales(), $this->maleApplicants());
  }

  function maleAcceptanceRate() {
    return StoredObject::formatFrac($this->rawMaleAcceptanceRate());
  }

  function rawFemaleAcceptanceRate() {
    return StoredObject::getFrac($this->admittedFemales(), $this->femaleApplicants());
  }

  function femaleAcceptanceRate() {
    return StoredObject::formatFrac($this->rawFemaleAcceptanceRate());
  }

  function rawEnrollRate() {
    return StoredObject::getFrac($this->enrolled(), $this->admitted());
  }

  function enrollRate() {
    return StoredObject::formatFrac($this->rawEnrollRate());
  }

  function rawMaleEnrollRate() {
    return StoredObject::getFrac($this->enrolledMales(), $this->admittedMales());
  }

  function maleEnrollRate() {
    return StoredObject::formatFrac($this->rawMaleEnrollRate());
  }

  function rawFemaleEnrollRate() {
    return StoredObject::getFrac($this->enrolledFemales(), $this->admittedFemales());
  }

  function femaleEnrollRate() {
    return StoredObject::formatFrac($this->rawFemaleEnrollRate());
  }

  function fullTimeEnrolled() {
    return $this->getWithBackup("enroll_ft", $this->fullTimeEnrolledMales(), $this->fullTimeEnrolledFemales());
  }

  function partTimeEnrolled() {
    return $this->getWithBackup("enroll_pt", $this->partTimeEnrolledMales(), $this->partTimeEnrolledFemales());
  }

  function rawProportionDisabled() {
    return $this->get("disabled");
  }

  function proportionDisabled() {
    return StoredObject::isKnown($this->rawProportionDisabled()) ? (round($this->rawProportionDisabled() * 100) . '%') : StoredObject::$UNKNOWN;
  }

  private static $campusHousing = array(1 => "Yes", 2 => "No", -1 => "Not Reported", -2 => "Not Applicable");
  function onCampusHousingProvided() {
    return School::$campusHousing[$this->get("campus_housing")];
  }

  private static $board = array(1 => "Yes, with constant meals per week", 2 => "Yes, meals per week varies", 3 => "No", -1 => "Not Reported", -2 => "Not Applicable");
  function boardProvided() {
    return School::$board[$this->get("board_provided")];
  }

  function boardStatement() {
    switch($this->get("board_provided")) {
      case 1:
        return "Offers a Meal Plan (" . $this->mealsPerWeek() . " meals per week)";
      case 2:
        return "Offers a Meal Plan (Meals per week varies)";
      case 3:
        return "Does not Offer a Meal Plan";
      default:
      case -1:
        return "Meal Plan Presence Not Reported";
      case -2:
        return "Meal Plan Presence Not Applicable";
    }
  }

  private static $liveOnCampus = array(1 => "Required to Live on Campus", 2 => "Not Required to Live on Campus", -1 => "Campus Requirement Not Reported", -2 => "Campus Requirement Not Applicable");
  function requiredToLiveOnCampus() {
    return School::$liveOnCampus[$this->get("campus_required")];
  }

  // TODO distance stuff

  function distanceStatement() {
    if($this->get("all_dist") == 1) {
      return "All Programs Offered via Distance";
    } else if($this->get("under_dist") == 1) {
      return "Undergraduate Programs Offered via Distance";
    } else if($this->get("grad_dist") == 1) {
      return "Graduate Programs Offered via Distance";
    } else if($this->get("no_dist") == 1) {
      return "No Programs Offered via Distance";
    } else {
      return "Programs Offered via Distance Unknown";
    }
  }
}

?>