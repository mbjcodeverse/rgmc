<?php
require_once "../controllers/userrights.controller.php";
require_once "../models/userrights.model.php";

class userRightsEntry{
  public $trans_type; 
  public $userid;
  public $username;
  public $upassword;
  public $empid;
  public $utype;
  public $ulevel;
  public $mmd;
  public $mip;
  public $mfp;
  public $mpc;
  public $mrm;
  public $mmr;
  public $md;
  public $mret;
  public $mir;
  public $minv;
  public $mrep;
  public $mirm;
  public $mifp;
  public $mcrm;
  public $mcfg;  
  public $mopr; 
  public $tmd;
  public $tmt;
  public $tmi;
  public $tpo;
  public $tis;
  public $trel;
  public $tret;
  public $tadj;
  public $tinv;
  public $trep;
  public $tprt;
  public $tcat;
  public $tbr;
  public $tmac;
  public $tcls;
  public $psup;
  public $pemp;
  public $paccess;
  public $plog;
  public $pcost;

  public function userRightsEntrySave(){
    $trans_type = $this->trans_type;
    $userid = $this->userid;
    $username = $this->username;
    $upassword = $this->upassword;
  	$empid = $this->empid;
    $utype = $this->utype;
    $ulevel = $this->ulevel;

  	$mmd = $this->mmd;
    $mip = $this->mip;
  	$mfp = $this->mfp;
    $mpc = $this->mpc;
  	$mrm = $this->mrm;
    $mmr = $this->mmr;
    $md = $this->md;
    $mret = $this->mret;
    $mir = $this->mir;
    $minv = $this->minv;
    $mrep = $this->mrep;
    $mirm = $this->mirm;
    $mifp = $this->mifp;
    $mcrm = $this->mcrm;
    $mcfg = $this->mcfg;
    $mopr = $this->mopr;

    $tmd = $this->tmd;
    $tmt = $this->tmt;
    $tmi = $this->tmi;
    $tpo = $this->tpo;
    $tis = $this->tis;
    $trel = $this->trel;
    $tret = $this->tret;
    $tadj = $this->tadj;
    $tinv = $this->tinv;
    $trep = $this->trep;
    $tprt = $this->tprt;
    $tcat = $this->tcat;
    $tbr = $this->tbr;
    $tmac = $this->tmac;
    $tcls = $this->tcls;

    $psup = $this->psup;
    $pemp = $this->pemp;
    $paccess = $this->paccess;
    $plog = $this->plog;
    $pcost = $this->pcost;

    $data = array("userid"=>$userid,
                  "username"=>$username,
                  "upassword"=>$upassword,
                  "empid"=>$empid,
                  "utype"=>$utype,
                  "ulevel"=>$ulevel,
                  "mmd"=>$mmd,
                  "mip"=>$mip,
                  "mfp"=>$mfp,
                  "mpc"=>$mpc,
                  "mrm"=>$mrm,
                  "mmr"=>$mmr,
                  "md"=>$md,
                  "mret"=>$mret,
                  "mir"=>$mir,
                  "minv"=>$minv,
                  "mrep"=>$mrep,
                  "mirm"=>$mirm,
                  "mifp"=>$mifp,
                  "mcrm"=>$mcrm,
                  "mcfg"=>$mcfg,
                  "mopr"=>$mopr,
                  "tmd"=>$tmd,
                  "tmt"=>$tmt,
                  "tmi"=>$tmi,
                  "tpo"=>$tpo,
                  "tis"=>$tis,
                  "trel"=>$trel,
                  "tret"=>$tret,
                  "tadj"=>$tadj,
                  "tinv"=>$tinv,
                  "trep"=>$trep,
                  "tprt"=>$tprt,
                  "tcat"=>$tcat,
                  "tbr"=>$tbr,
                  "tmac"=>$tmac,
                  "tcls"=>$tcls,
                  "psup"=>$psup,
                  "pemp"=>$pemp,
                  "paccess"=>$paccess,
                  "plog"=>$plog,
                  "pcost"=>$pcost);

    if ($trans_type == 'New'){
      $answer = (new ControllerUserRights)->ctrAddUserRights($data);
      echo $answer;
    }else{
      $answer = (new ControllerUserRights)->ctrEditUserRights($data);
      echo $answer;
    }

  }
}

$inputUserRights = new userRightsEntry();

$inputUserRights -> trans_type = $_POST["trans_type"];
$inputUserRights -> userid = $_POST["userid"];
$inputUserRights -> username = $_POST["username"];
$inputUserRights -> upassword = $_POST["upassword"];
$inputUserRights -> empid = $_POST["empid"];
$inputUserRights -> utype = $_POST["utype"];
$inputUserRights -> ulevel = $_POST["ulevel"];
$inputUserRights -> mmd = $_POST["mmd"];
$inputUserRights -> mip = $_POST["mip"];
$inputUserRights -> mfp = $_POST["mfp"];
$inputUserRights -> mpc = $_POST["mpc"];
$inputUserRights -> mrm = $_POST["mrm"];
$inputUserRights -> mmr = $_POST["mmr"];
$inputUserRights -> md = $_POST["md"];
$inputUserRights -> mret = $_POST["mret"];
$inputUserRights -> mir = $_POST["mir"];
$inputUserRights -> minv = $_POST["minv"];
$inputUserRights -> mrep = $_POST["mrep"];
$inputUserRights -> mirm = $_POST["mirm"];
$inputUserRights -> mifp = $_POST["mifp"];
$inputUserRights -> mcrm = $_POST["mcrm"];
$inputUserRights -> mcfg = $_POST["mcfg"];
$inputUserRights -> mopr = $_POST["mopr"];

$inputUserRights -> tmd = $_POST["tmd"];
$inputUserRights -> tmt = $_POST["tmt"];
$inputUserRights -> tmi = $_POST["tmi"];
$inputUserRights -> tpo = $_POST["tpo"];
$inputUserRights -> tis = $_POST["tis"];
$inputUserRights -> trel = $_POST["trel"];
$inputUserRights -> tret = $_POST["tret"];
$inputUserRights -> tadj = $_POST["tadj"];
$inputUserRights -> tinv = $_POST["tinv"];
$inputUserRights -> trep = $_POST["trep"];
$inputUserRights -> tprt = $_POST["tprt"];
$inputUserRights -> tcat = $_POST["tcat"];
$inputUserRights -> tbr = $_POST["tbr"];
$inputUserRights -> tmac = $_POST["tmac"];
$inputUserRights -> tcls = $_POST["tcls"];

$inputUserRights -> psup = $_POST["psup"];
$inputUserRights -> pemp = $_POST["pemp"];
$inputUserRights -> paccess = $_POST["paccess"];
$inputUserRights -> plog = $_POST["plog"];
$inputUserRights -> pcost = $_POST["pcost"];

$inputUserRights -> userRightsEntrySave();