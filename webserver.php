<?php
//error_reporting( E_ALL | E_STRICT );
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_COMPILE_ERROR);
ini_set('display_errors','On');

//include required files
require_once './config.inc.php';
require_once './database.php';
require_once './utility.php';
require_once './xml/validation.php';

/*
//intialize the logger
$log = new Logger('./logs/webserver.log');

$log->emptyLogFile();
*/



//some debuggin info
/*
echo "======================= SERVER REPLY =======================";
echo "\n-User agent: ".$_SERVER['HTTP_USER_AGENT'];
echo "\n-Ip: ".$_SERVER['REMOTE_ADDR'];
echo "\n-Time: ".$_SERVER['REQUEST_TIME'];
*/

//echo "\n-Data: ".$_POST['data'];

/*
$_POST['data']='<?xml version="1.0" encoding="UTF-8"?>
<content>
<request>
<races>
<user_id>1</user_id>
<user_skill>0</user_skill>
<track_id>a-speedway</track_id>
<car_id>ls2-condor-c400r</car_id>
<type>0</type>
<setup><![CDATA[<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE params SYSTEM "params.dtd">


<params name="(null)">
  <section name="Car">
    <attstr name="category" val="LS-GT2"/>
    <attnum name="body length" unit="m" min="4.5" max="5" val="4.8"/>
    <attnum name="body width" unit="m" min="0.8" max="2.2" val="2.1"/>
    <attnum name="body height" unit="m" max="1.7" val="1.1"/>
    <attnum name="overall length" unit="m" min="4.5" max="5" val="4.58"/>
    <attnum name="overall width" unit="m" min="1.2" max="2.2" val="1.96"/>
    <attnum name="mass" unit="kg" val="1290"/>
    <attnum name="GC height" unit="m" val="0.17"/>
    <attnum name="front-rear weight repartition" val="0.52"/>
    <attnum name="front right-left weight repartition" min="0.3" max="0.7" val="0.5"/>
    <attnum name="rear right-left weight repartition" min="0.3" max="0.7" val="0.5"/>
    <attnum name="mass repartition coefficient" val="0.75"/>
    <attnum name="fuel tank" unit="l" val="100"/>
    <attnum name="initial fuel" val="16.7932"/>
  </section>

  <section name="Aerodynamics">
    <attnum name="Cx" val="0.36"/>
    <attnum name="front area" unit="m2" val="1.97"/>
    <attnum name="front Clift" val="0.28"/>
    <attnum name="rear Clift" val="0.25"/>
  </section>

  <section name="Front Wing">
    <attnum name="area" unit="m2" val="0.175"/>
    <attnum name="angle" unit="deg" min="3" val="11"/>
    <attnum name="xpos" unit="m" val="2.23"/>
    <attnum name="zpos" unit="m" val="0.05"/>
  </section>

  <section name="Rear Wing">
    <attnum name="area" unit="m2" val="0.3"/>
    <attnum name="angle" unit="deg" min="4" max="21" val="15"/>
    <attnum name="xpos" unit="m" val="-1.95"/>
    <attnum name="zpos" unit="m" val="0.95"/>
  </section>

  <section name="Engine">
    <attnum name="revs maxi" unit="rpm" val="9000"/>
    <attnum name="revs limiter" unit="rpm" val="8500"/>
    <attnum name="tickover" unit="rpm" val="900"/>
    <attnum name="fuel cons factor" val="1.1"/>
    <attnum name="torque def step" unit="rpm" val="1000"/>
    <attnum name="inertia" unit="kg.m2" val="0.2"/>
    <attnum name="torque min" unit="N.m" val="0"/>
    <attnum name="torque max" unit="N.m" val="2000"/>
    <attnum name="revs maxi min" unit="rpm" val="1000"/>
    <attnum name="revs maxi max" unit="rpm" val="20000"/>
    <attnum name="power max" unit="ch" val="1500"/>
    <attstr name="turbo" val="false"/>
    <attstr name="capacity" val="4.2"/>
    <attnum name="cylinders" val="6"/>
    <attstr name="shape" in="v,l,h,w" val="l"/>
    <attstr name="position" in="front,front-mid,mid,rear-mid,rear" val="front"/>
    <attnum name="brake linear coefficient" val="0.0539657"/>
    <attnum name="brake coefficient" val="0.0516954"/>
    <section name="data points">
      <section name="1">
        <attnum name="rpm" unit="rpm" val="0"/>
        <attnum name="Tq" unit="N.m" val="100"/>
      </section>

      <section name="2">
        <attnum name="rpm" unit="rpm" val="500"/>
        <attnum name="Tq" unit="N.m" val="135.9"/>
      </section>

      <section name="3">
        <attnum name="rpm" unit="rpm" val="1000"/>
        <attnum name="Tq" unit="N.m" val="243.6"/>
      </section>

      <section name="4">
        <attnum name="rpm" unit="rpm" val="1500"/>
        <attnum name="Tq" unit="N.m" val="410.9"/>
      </section>

      <section name="5">
        <attnum name="rpm" unit="rpm" val="2000"/>
        <attnum name="Tq" unit="N.m" val="465"/>
      </section>

      <section name="6">
        <attnum name="rpm" unit="rpm" val="2500"/>
        <attnum name="Tq" unit="N.m" val="515.3"/>
      </section>

      <section name="7">
        <attnum name="rpm" unit="rpm" val="3000"/>
        <attnum name="Tq" unit="N.m" val="531.7"/>
      </section>

      <section name="8">
        <attnum name="rpm" unit="rpm" val="3500"/>
        <attnum name="Tq" unit="N.m" val="551.2"/>
      </section>

      <section name="9">
        <attnum name="rpm" unit="rpm" val="4000"/>
        <attnum name="Tq" unit="N.m" val="562.3"/>
      </section>

      <section name="10">
        <attnum name="rpm" unit="rpm" val="4500"/>
        <attnum name="Tq" unit="N.m" val="575.7"/>
      </section>

      <section name="11">
        <attnum name="rpm" unit="rpm" val="5000"/>
        <attnum name="Tq" unit="N.m" val="588.7"/>
      </section>

      <section name="12">
        <attnum name="rpm" unit="rpm" val="5500"/>
        <attnum name="Tq" unit="N.m" val="597.7"/>
      </section>

      <section name="13">
        <attnum name="rpm" unit="rpm" val="6000"/>
        <attnum name="Tq" unit="N.m" val="600"/>
      </section>

      <section name="14">
        <attnum name="rpm" unit="rpm" val="6500"/>
        <attnum name="Tq" unit="N.m" val="598.7"/>
      </section>

      <section name="15">
        <attnum name="rpm" unit="rpm" val="7000"/>
        <attnum name="Tq" unit="N.m" val="529.3"/>
      </section>

      <section name="16">
        <attnum name="rpm" unit="rpm" val="7500"/>
        <attnum name="Tq" unit="N.m" val="436.7"/>
      </section>

      <section name="17">
        <attnum name="rpm" unit="rpm" val="8000"/>
        <attnum name="Tq" unit="N.m" val="315"/>
      </section>

      <section name="18">
        <attnum name="rpm" unit="rpm" val="8500"/>
        <attnum name="Tq" unit="N.m" val="65"/>
      </section>

      <section name="19">
        <attnum name="rpm" unit="rpm" val="9000"/>
        <attnum name="Tq" unit="N.m" val="25"/>
      </section>

      <section name="20">
        <attnum name="rpm" unit="rpm" val="10000"/>
        <attnum name="Tq" unit="N.m" val="0"/>
      </section>

      <section name="21">
        <attnum name="rpm" unit="rpm" val="11000"/>
        <attnum name="Tq" unit="N.m" val="0"/>
      </section>

    </section>

  </section>

  <section name="Clutch">
    <attnum name="inertia" unit="kg.m2" val="0.115"/>
  </section>

  <section name="Gearbox">
    <attnum name="shift time" unit="s" val="0.15"/>
    <section name="gears">
      <section name="r">
        <attnum name="ratio" min="-3" max="0" val="-2"/>
        <attnum name="inertia" val="0.0037"/>
        <attnum name="efficiency" val="0.95"/>
      </section>

      <section name="1">
        <attnum name="ratio" min="0" max="9" val="3"/>
        <attnum name="inertia" val="0.003"/>
        <attnum name="efficiency" val="0.95"/>
      </section>

      <section name="2">
        <attnum name="ratio" min="0" max="5" val="2.1"/>
        <attnum name="inertia" val="0.0037"/>
        <attnum name="efficiency" val="0.96"/>
      </section>

      <section name="3">
        <attnum name="ratio" min="0" max="5" val="1.4"/>
        <attnum name="inertia" val="0.0048"/>
        <attnum name="efficiency" val="0.97"/>
      </section>

      <section name="4">
        <attnum name="ratio" min="0" max="5" val="1.18"/>
        <attnum name="inertia" val="0.0064"/>
        <attnum name="efficiency" val="0.96"/>
      </section>

      <section name="5">
        <attnum name="ratio" min="0" max="5" val="1.04"/>
        <attnum name="inertia" val="0.0107"/>
        <attnum name="efficiency" val="0.96"/>
      </section>

      <section name="6">
        <attnum name="ratio" min="0" max="5" val="0.95"/>
        <attnum name="inertia" val="0.015"/>
        <attnum name="efficiency" val="0.96"/>
      </section>

    </section>

  </section>

  <section name="Drivetrain">
    <attstr name="type" val="RWD"/>
    <attnum name="inertia" unit="kg.m2" val="0.0091"/>
  </section>

  <section name="Steer">
    <attnum name="steer lock" unit="deg" val="23"/>
    <attnum name="max steer speed" unit="deg/s" val="540"/>
  </section>

  <section name="Brake System">
    <attnum name="front-rear brake repartition" min="0.3" max="0.8" val="0.55"/>
    <attnum name="max pressure" unit="kPa" min="100" max="16000" val="15000"/>
    <attnum name="emergency brake pressure" unit="kPa" max="1.5e+06" val="0"/>
  </section>

  <section name="Front Axle">
    <attnum name="xpos" val="1.43"/>
    <attnum name="inertia" unit="kg.m2" val="0.0056"/>
    <attnum name="roll center height" unit="m" val="0.115"/>
  </section>

  <section name="Rear Axle">
    <attnum name="xpos" val="-1.24"/>
    <attnum name="inertia" unit="kg.m2" val="0.008"/>
    <attnum name="roll center height" unit="m" val="0.115"/>
  </section>

  <section name="Front Differential">
    <attstr name="type" in="NONE,NONE" val="NONE"/>
  </section>

  <section name="Rear Differential">
    <attstr name="type" in="SPOOL,FREE,LIMITED SLIP,SPOOL,FREE,LIMITED SLIP" val="LIMITED SLIP"/>
    <attnum name="inertia" unit="kg.m2" val="0.0488"/>
    <attnum name="ratio" min="1" max="10" val="3.5"/>
    <attnum name="efficiency" val="0.97"/>
  </section>

  <section name="Front Right Wheel">
    <attnum name="ypos" unit="m" val="-0.79"/>
    <attnum name="rim diameter" unit="in" val="17"/>
    <attnum name="tire width" unit="mm" val="270"/>
    <attnum name="tire height-width ratio" val="0.39"/>
    <attnum name="inertia" unit="kg.m2" val="0.7141"/>
    <attnum name="ride height" unit="mm" min="100" max="140" val="105"/>
    <attnum name="toe" unit="deg" min="-5" max="5" val="-0.1"/>
    <attnum name="stiffness" min="14" max="30" val="25"/>
    <attnum name="dynamic friction" unit="%" val="60"/>
    <attnum name="rolling resistance" val="0.02"/>
    <attnum name="mu" val="1.3"/>
    <attnum name="camber" unit="deg" max="0" val="-5"/>
    <attnum name="mass" unit="kg" val="19.4481"/>
    <attnum name="operating load" unit="kg" val="3078"/>
    <attnum name="load factor min" val="0.6"/>
    <attnum name="load factor max" val="1.6"/>
  </section>

  <section name="Front Left Wheel">
    <attnum name="ypos" unit="m" val="0.79"/>
    <attnum name="rim diameter" unit="in" val="17"/>
    <attnum name="tire width" unit="mm" val="270"/>
    <attnum name="tire height-width ratio" val="0.39"/>
    <attnum name="inertia" unit="kg.m2" val="0.7141"/>
    <attnum name="ride height" unit="mm" min="100" max="140" val="105"/>
    <attnum name="toe" unit="deg" min="-5" max="5" val="0.1"/>
    <attnum name="stiffness" min="14" max="30" val="25"/>
    <attnum name="dynamic friction" unit="%" val="60"/>
    <attnum name="rolling resistance" val="0.02"/>
    <attnum name="mu" val="1.3"/>
    <attnum name="camber" unit="deg" max="0" val="-5"/>
    <attnum name="mass" unit="kg" val="19.4481"/>
    <attnum name="operating load" unit="kg" val="3078"/>
    <attnum name="load factor min" val="0.6"/>
    <attnum name="load factor max" val="1.6"/>
  </section>

  <section name="Rear Right Wheel">
    <attnum name="ypos" unit="m" val="-0.78"/>
    <attnum name="rim diameter" unit="in" val="17"/>
    <attnum name="tire width" unit="mm" val="330"/>
    <attnum name="tire height-width ratio" val="0.33"/>
    <attnum name="inertia" unit="kg.m2" val="0.8366"/>
    <attnum name="ride height" unit="mm" min="100" max="140" val="115"/>
    <attnum name="toe" unit="deg" min="-5" max="5" val="0"/>
    <attnum name="stiffness" min="14" max="30" val="25"/>
    <attnum name="dynamic friction" unit="%" val="60"/>
    <attnum name="rolling resistance" val="0.02"/>
    <attnum name="mu" val="1.3"/>
    <attnum name="camber" unit="deg" max="0" val="-1.5"/>
    <attnum name="mass" unit="kg" val="22.5956"/>
    <attnum name="operating load" unit="kg" val="3762"/>
    <attnum name="load factor min" val="0.6"/>
    <attnum name="load factor max" val="1.6"/>
  </section>

  <section name="Rear Left Wheel">
    <attnum name="ypos" unit="m" val="0.78"/>
    <attnum name="rim diameter" unit="in" val="17"/>
    <attnum name="tire width" unit="mm" val="330"/>
    <attnum name="tire height-width ratio" val="0.33"/>
    <attnum name="inertia" unit="kg.m2" val="0.8366"/>
    <attnum name="ride height" unit="mm" min="100" max="140" val="115"/>
    <attnum name="toe" unit="deg" min="-5" max="5" val="0"/>
    <attnum name="stiffness" min="14" max="30" val="25"/>
    <attnum name="dynamic friction" unit="%" val="60"/>
    <attnum name="rolling resistance" val="0.02"/>
    <attnum name="mu" val="1.3"/>
    <attnum name="camber" unit="deg" max="0" val="-1.5"/>
    <attnum name="mass" unit="kg" val="22.5956"/>
    <attnum name="operating load" unit="kg" val="3762"/>
    <attnum name="load factor min" val="0.6"/>
    <attnum name="load factor max" val="1.6"/>
  </section>

  <section name="Front Anti-Roll Bar">
    <attnum name="spring" unit="kN/m" min="0" max="50" val="20"/>
    <attnum name="suspension course" unit="m" min="0" max="0.25" val="0.2"/>
    <attnum name="bellcrank" min="1" max="5" val="2.5"/>
  </section>

  <section name="Rear Anti-Roll Bar">
    <attnum name="spring" unit="kN/m" min="0" max="50" val="15"/>
    <attnum name="suspension course" unit="m" min="0" max="0.25" val="0.2"/>
    <attnum name="bellcrank" min="1" max="5" val="2.5"/>
  </section>

  <section name="Front Right Suspension">
    <attnum name="spring" unit="kN/m" min="0" max="300" val="71.4"/>
    <attnum name="suspension course" unit="m" min="0" max="0.15" val="0.13"/>
    <attnum name="bellcrank" val="1"/>
    <attnum name="packers" unit="mm" max="10" val="0"/>
    <attnum name="slow bump" unit="kN/m/s" val="8.01697"/>
    <attnum name="slow rebound" unit="kN/m/s" val="12.3338"/>
    <attnum name="fast bump" unit="kN/m/s" val="2.67232"/>
    <attnum name="fast rebound" unit="kN/m/s" val="4.11126"/>
    <attstr name="suspension type" val="Wishbone"/>
    <attnum name="bump limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
    <attnum name="rebound limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
  </section>

  <section name="Front Left Suspension">
    <attnum name="spring" unit="kN/m" min="0" max="300" val="71.4"/>
    <attnum name="suspension course" unit="m" min="0" max="0.15" val="0.13"/>
    <attnum name="bellcrank" val="1"/>
    <attnum name="packers" unit="mm" max="10" val="0"/>
    <attnum name="slow bump" unit="kN/m/s" val="8.01697"/>
    <attnum name="slow rebound" unit="kN/m/s" val="12.3338"/>
    <attnum name="fast bump" unit="kN/m/s" val="2.67232"/>
    <attnum name="fast rebound" unit="kN/m/s" val="4.11126"/>
    <attstr name="suspension type" val="Wishbone"/>
    <attnum name="bump limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
    <attnum name="rebound limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
  </section>

  <section name="Rear Right Suspension">
    <attnum name="spring" unit="kN/m" min="0" max="300" val="47.6"/>
    <attnum name="suspension course" unit="m" min="0" max="0.13" val="0.12"/>
    <attnum name="bellcrank" val="1"/>
    <attnum name="packers" unit="mm" max="50" val="0"/>
    <attnum name="slow bump" unit="kN/m/s" val="6.60546"/>
    <attnum name="slow rebound" unit="kN/m/s" val="10.1623"/>
    <attnum name="fast bump" unit="kN/m/s" val="2.20182"/>
    <attnum name="fast rebound" unit="kN/m/s" val="3.38742"/>
    <attstr name="suspension type" val="Wishbone"/>
    <attnum name="bump limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
    <attnum name="rebound limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
  </section>

  <section name="Rear Left Suspension">
    <attnum name="spring" unit="kN/m" min="0" max="300" val="47.6"/>
    <attnum name="suspension course" unit="m" min="0" max="0.13" val="0.12"/>
    <attnum name="bellcrank" val="1"/>
    <attnum name="packers" unit="mm" max="10" val="0"/>
    <attnum name="slow bump" unit="kN/m/s" val="6.60546"/>
    <attnum name="slow rebound" unit="kN/m/s" val="10.1623"/>
    <attnum name="fast bump" unit="kN/m/s" val="2.20182"/>
    <attnum name="fast rebound" unit="kN/m/s" val="3.38742"/>
    <attstr name="suspension type" val="Wishbone"/>
    <attnum name="bump limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
    <attnum name="rebound limit velocity" unit="m/s" min="0.1" max="0.3" val="0.2"/>
  </section>

  <section name="Front Right Brake">
    <attnum name="disk diameter" unit="mm" val="380"/>
    <attnum name="piston area" unit="cm2" val="50"/>
    <attnum name="mu" min="0.1" max="0.5" val="0.45"/>
    <attnum name="inertia" unit="kg.m2" val="0.25"/>
  </section>

  <section name="Front Left Brake">
    <attnum name="disk diameter" unit="mm" val="380"/>
    <attnum name="piston area" unit="cm2" val="50"/>
    <attnum name="mu" min="0.1" max="0.5" val="0.45"/>
    <attnum name="inertia" unit="kg.m2" val="0.25"/>
  </section>

  <section name="Rear Right Brake">
    <attnum name="disk diameter" unit="mm" val="355"/>
    <attnum name="piston area" unit="cm2" val="33"/>
    <attnum name="mu" min="0.1" max="0.5" val="0.45"/>
    <attnum name="inertia" unit="kg.m2" val="0.17"/>
  </section>

  <section name="Rear Left Brake">
    <attnum name="disk diameter" unit="mm" val="355"/>
    <attnum name="piston area" unit="cm2" val="33"/>
    <attnum name="mu" min="0.1" max="0.5" val="0.45"/>
    <attnum name="inertia" unit="kg.m2" val="0.17"/>
  </section>

  <section name="Bonnet">
    <attnum name="xpos" unit="m" val="0.75"/>
    <attnum name="ypos" unit="m" val="0"/>
    <attnum name="zpos" unit="m" val="0.95"/>
  </section>

  <section name="Driver">
    <attnum name="xpos" unit="m" val="-0.3"/>
    <attnum name="ypos" unit="m" val="0.35"/>
    <attnum name="zpos" unit="m" val="0.8"/>
  </section>

  <section name="Sound">
    <attstr name="engine sample" val="v808.wav"/>
    <attnum name="rpm scale" val="1.25"/>
  </section>

  <section name="Graphic Objects">
    <attstr name="env" val="ls2-condor-c400r.acc"/>
    <attstr name="wheel texture" val="tex-wheel.rgb"/>
    <attstr name="shadow texture" val="shadow.rgb"/>
    <attstr name="tachometer texture" val="rpm8500.png"/>
    <attnum name="tachometer min value" unit="rpm" val="0"/>
    <attnum name="tachometer max value" unit="rpm" val="10000"/>
    <attstr name="speedometer texture" val="speed300.png"/>
    <attnum name="speedometer min value" unit="km/h" val="0"/>
    <attnum name="speedometer max value" unit="km/h" val="300"/>
    <section name="Ranges">
      <section name="1">
        <attnum name="threshold" val="0"/>
        <attstr name="car" val="ls2-condor-c400r.acc"/>
        <attstr name="wheels" val="yes"/>
      </section>

    </section>

    <section name="Light">
      <section name="1">
        <attstr name="type" val="brake"/>
        <attnum name="xpos" val="-2.21"/>
        <attnum name="ypos" val="0.54"/>
        <attnum name="zpos" val="0.5"/>
        <attnum name="size" val="0.11"/>
      </section>

      <section name="2">
        <attstr name="type" val="brake"/>
        <attnum name="xpos" val="-2.21"/>
        <attnum name="ypos" val="-0.54"/>
        <attnum name="zpos" val="0.5"/>
        <attnum name="size" val="0.11"/>
      </section>

      <section name="3">
        <attstr name="type" val="brake"/>
        <attnum name="xpos" val="-2.23"/>
        <attnum name="ypos" val="0.45"/>
        <attnum name="zpos" val="0.4"/>
        <attnum name="size" val="0.11"/>
      </section>

      <section name="4">
        <attstr name="type" val="brake"/>
        <attnum name="xpos" val="-2.23"/>
        <attnum name="ypos" val="-0.45"/>
        <attnum name="zpos" val="0.4"/>
        <attnum name="size" val="0.11"/>
      </section>

      <section name="5">
        <attstr name="type" val="rear"/>
        <attnum name="xpos" val="-2.21"/>
        <attnum name="ypos" val="0.54"/>
        <attnum name="zpos" val="0.5"/>
        <attnum name="size" val="0.11"/>
      </section>

      <section name="6">
        <attstr name="type" val="rear"/>
        <attnum name="xpos" val="-2.21"/>
        <attnum name="ypos" val="-0.54"/>
        <attnum name="zpos" val="0.5"/>
        <attnum name="size" val="0.11"/>
      </section>

      <section name="7">
        <attstr name="type" val="head1"/>
        <attnum name="xpos" val="1.78"/>
        <attnum name="ypos" val="0.71"/>
        <attnum name="zpos" val="0.49"/>
        <attnum name="size" val="0.13"/>
      </section>

      <section name="8">
        <attstr name="type" val="head1"/>
        <attnum name="xpos" val="1.78"/>
        <attnum name="ypos" val="-0.71"/>
        <attnum name="zpos" val="0.49"/>
        <attnum name="size" val="0.13"/>
      </section>

      <section name="9">
        <attstr name="type" val="head1"/>
        <attnum name="xpos" val="1.98"/>
        <attnum name="ypos" val="0.6"/>
        <attnum name="zpos" val="0.34"/>
        <attnum name="size" val="0.12"/>
      </section>

      <section name="10">
        <attstr name="type" val="head1"/>
        <attnum name="xpos" val="1.98"/>
        <attnum name="ypos" val="-0.6"/>
        <attnum name="zpos" val="0.34"/>
        <attnum name="size" val="0.12"/>
      </section>

      <section name="11">
        <attstr name="type" val="head2"/>
        <attnum name="xpos" val="1.89"/>
        <attnum name="ypos" val="0.6"/>
        <attnum name="zpos" val="0.42"/>
        <attnum name="size" val="0.09"/>
      </section>

      <section name="12">
        <attstr name="type" val="head2"/>
        <attnum name="xpos" val="1.89"/>
        <attnum name="ypos" val="-0.6"/>
        <attnum name="zpos" val="0.42"/>
        <attnum name="size" val="0.09"/>
      </section>

    </section>

  </section>

  <section name="Exhaust">
    <attnum name="power" val="1.5"/>
    <section name="1">
      <attnum name="xpos" val="-0.83"/>
      <attnum name="ypos" val="-1.1"/>
      <attnum name="zpos" val="0.1"/>
    </section>

    <section name="2">
      <attnum name="xpos" val="-0.83"/>
      <attnum name="ypos" val="1.1"/>
      <attnum name="zpos" val="0.1"/>
    </section>

  </section>

  <section name="Features">
    <attstr name="fixed low speed grip" in="yes,no" val="yes"/>
    <attstr name="realistic gear change" in="yes,no" val="no"/>
    <attstr name="realistic rev limiter" in="yes,no" val="yes"/>
    <attstr name="tire temperature and degradation" in="yes,no" val="no"/>
    <attstr name="fixed wheel force" in="yes,no" val="no"/>
  </section>

</params>
]]></setup>
<position>1</position>
<sdversion>2.2-trunk-r5883m</sdversion>
</races>
</request>
</content>';
*/
//log something

//$log->info($_SERVER["REQUEST_URI"]);

//$log->info(implode(array_keys($_POST)));
//$log->info($_SERVER['REMOTE_ADDR']." || ".$_SERVER['REQUEST_TIME']." || ");

//$log->info($_POST['data']);



//initialize the database
$myDb=new DataBase($config->database);

//read the xml
//$xml=new MyXml(file_get_contents('./xml/lap.xml'));
//$xml=new MyXml($_POST['data']);
/*
$xml=xmlObj('<?xml version="1.0" encoding="UTF-8"?><content><request><laps><race_id>1</race_id><laptime>24.776000</laptime><fuel>7.057418</fuel><position>1</position></laps></request></content>');
*/
/*
$_POST['data']='<?xml version="1.0" encoding="UTF-8"?><content><request><login><username>mad_joypad</username><password>password</password></login></request></content>';
*/


$xml=xmlObj($_POST['data']);

//$xml->request->races->id=1;

//log the ip /*hack... if there are more laps?*/
//$xmlData->lap->user->ip = $_SERVER['REMOTE_ADDR'];


//echo 'Database structure:';
//print_r($xmlData->getDataArray());

//validate the data
//$xmlData->validate();

/*
<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="races">
				<attnum name="id" val="'.$conditions->id.'"/>
			</section>
		</section>
	</section>
</params>

<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="messages">
				<attnum name="number" val="1"/>
				<attstr name="message0" val="msgserver"/>
			</section>
			<section name="races">
				<attnum name="id" val="'.$myDb->lastInsertId.'"/>
			</section>
		</section>
	</section>
</params>

<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attstr name="type" val="races"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="laps">
				<attnum name="id" val="'.$myDb->lastInsertId.'"/>
			</section>
			<section name="messages">
				<attnum name="number" val="3"/>
				<attstr name="message0" val="So you have\n a good lap on the go"/>
				<attstr name="message1" val="good to kwno"/>
				<attstr name="message2" val="best lap"/>
			</section>
		</section>
	</section>
</params>
				
<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val="0.1"/>
		<attstr name="type" val="races"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="login">
				<attstr name="sessionid" val="'.$user['sessionid'].'"/>
				<attnum name="id" val="'. $user['id'].'"/>
			</section>
		</section>
	</section>
</params>


$string='<?xml version="1.0" encoding="UTF-8"?>
<params name="webServerReply">
	<section name="content">
		<attnum name="webServerVersion" val=""/>
		<attstr name="type" val="races"/>
		<attnum name="date" val="12324564"/>
		<attstr name="error" val=""/>
		<section name="reply">
			<section name="login">
				<attstr name="sessionid" val=""/>
				<attnum name="id" val=""/>
			</section>
		</section>
	</section>
</params>';
*/



//setup the initial xml data for the reply
$webserverversion= 1;
$string='<?xml version="1.0" encoding="UTF-8"?>
<params>
</params>';

$xmlreply= new SimpleXMLElement($string);
//$xmlreply->preserveWhiteSpace = false;
//$xmlreply->formatOutput = true;
$temp=$xmlreply->xpath('/params');
$params = $temp[0];//size"[@label='Large']");

$params->addAttribute('name','webServerReply');

$content = $params->addChild('section');
$content->addAttribute('name', 'content');

$requestid =  $content->addChild('attnum');
$requestid->addAttribute('name','request_id');
$requestid->addAttribute('val',$xml->request_id);


$version =  $content->addChild('attnum');
$version->addAttribute('name','webServerVersion');
$version->addAttribute('val',$webserverversion);

$date =  $content->addChild('attnum');
$date->addAttribute('name','date');
$date->addAttribute('val',time());

$error =  $content->addChild('attstr');
$error->addAttribute('name','error');
$error->addAttribute('val','this is an error');

$reply = $content->addChild('section');
$reply->addAttribute('name','reply');

//process the request
foreach ($xml->request as $requestype => $requestdata){
	if(property_exists($requestdata,'id')){
		//there is already an id assigned, update the old data into the database
		$conditions = new stdClass;
		$conditions->id=$requestdata->id;
		$myDb->update($requestdata, $requestype, $conditions);

		//xml
		$races =  $reply->addChild('section');
		$races->addAttribute('name','races');
		
		$id = $races->addChild('attnum');
		$id->addAttribute('name', 'id');
		$id->addAttribute('val', $conditions->id);
		//xml messages
		$messagges =  $reply->addChild('section');
		$messagges->addAttribute('name','messages');

		$number =  $messagges->addChild('attnum');
		$number->addAttribute('name','number');
		$number->addAttribute('val', 1);

		$msg0 =  $messagges->addChild('attstr');
		$msg0->addAttribute('name','message0');	
		$msg0->addAttribute('val',"Final race position registered\nfrom the server");
		/*
		echo '<?xml version="1.0" encoding="UTF-8"?>
		<params name="webServerReply">
			<section name="content">
				<attnum name="webServerVersion" val="0.1"/>
				<attstr name="type" val="races"/>
				<attnum name="date" val="12324564"/>
				<attstr name="error" val=""/>
				<section name="reply">
					<section name="races">
						<attnum name="id" val="'.$conditions->id.'"/>
					</section>
				</section>
			</section>
		</params>';
		*/
	}else{
		//this is new data, insert it into the database

		//$assignedId = mysql_insert_id();
		//echo $myDb->lastInsertId;
		switch ($requestype){
			case 'races':
				$result=$myDb->insert($requestdata, $requestype);
				
				//xml
				$races =  $reply->addChild('section');
				$races->addAttribute('name','races');
				
				$id = $races->addChild('attnum');
				$id->addAttribute('name', 'id');
				$id->addAttribute('val', $myDb->lastInsertId);
				


				//select the best lap for this car/track combo
				$query="
				SELECT min(A.laptime) as bestlap
				  FROM laps A
				INNER
				  JOIN races B
					ON A.race_id = B.id
				WHERE
					B.car_id = '".$requestdata->car_id."'
					AND B.track_id = '".$requestdata->track_id."'
				";
				$bestlap = $myDb->customSelect($query);



				//xml messages
				$messagges =  $reply->addChild('section');
				$messagges->addAttribute('name','messages');

				$number =  $messagges->addChild('attnum');
				$number->addAttribute('name','number');
				$number->addAttribute('val', 1);

				$msg0 =  $messagges->addChild('attstr');
				$msg0->addAttribute('name','message0');	
				$msg0->addAttribute('val',"Race registered\nfrom the server\n-\nYour best lap with this\ncar/track combo is\n".formatLaptime($bestlap[0]['bestlap']));				



				
				/*
				echo '<?xml version="1.0" encoding="UTF-8"?>
				<params name="webServerReply">
					<section name="content">
						<attnum name="webServerVersion" val="0.1"/>
						<attstr name="type" val="races"/>
						<attnum name="date" val="12324564"/>
						<attstr name="error" val=""/>
						<section name="reply">
							<section name="messages">
								<attnum name="number" val="1"/>
								<attstr name="message0" val="msgserver"/>
							</section>
							<section name="races">
								<attnum name="id" val="'.$myDb->lastInsertId.'"/>
							</section>
						</section>
					</section>
				</params>';
				*/
			break;
			case 'laps':
				$result=$myDb->insert($requestdata, $requestype);
				
				//xml
				$laps =  $reply->addChild('section');
				$laps->addAttribute('name','laps');
				
				$id = $laps->addChild('attnum');
				$id->addAttribute('name', 'id');
				$id->addAttribute('val', $myDb->lastInsertId);
				
				//select the car and track id for this race
				$query="
				SELECT track_id, car_id
				FROM races
				WHERE
					id =".$requestdata->race_id." 
				";
				$racedata = $myDb->customSelect($query);					
								
				//select the best lap for this car/track combo
				$query="
				SELECT min(A.laptime) as bestlap
				  FROM laps A
				INNER
				  JOIN races B
					ON A.race_id = B.id
				WHERE
					B.car_id = '".$racedata[0]['car_id']."'
					AND B.track_id = '".$racedata[0]['track_id']."'
				";
				$bestlap = $myDb->customSelect($query);				
				
				
				
				//xml messages
				$messagges =  $reply->addChild('section');
				$messagges->addAttribute('name','messages');

				$number =  $messagges->addChild('attnum');
				$number->addAttribute('name','number');
				$number->addAttribute('val', 1);

				$msg0 =  $messagges->addChild('attstr');
				$msg0->addAttribute('name','message0');	
				$msg0->addAttribute('val',"Position:".$requestdata->position."\nFuel:".$requestdata->fuel."\nLap:Best: ".formatLaptime($bestlap[0]['bestlap'])."\nLast: ".formatLaptime($requestdata->laptime)."\n Diff: ".formatLaptime($requestdata->laptime-$bestlap[0]['bestlap']));
				
				/*
				echo '<?xml version="1.0" encoding="UTF-8"?>
				<params name="webServerReply">
					<section name="content">
						<attnum name="webServerVersion" val="0.1"/>
						<attstr name="type" val="races"/>
						<attnum name="date" val="12324564"/>
						<attstr name="error" val=""/>
						<section name="reply">
							<section name="laps">
								<attnum name="id" val="'.$myDb->lastInsertId.'"/>
							</section>
							<section name="messages">
								<attnum name="number" val="3"/>
								<attstr name="message0" val="So you have\n a good lap on the go"/>
								<attstr name="message1" val="good to kwno"/>
								<attstr name="message2" val="best lap"/>
							</section>
						</section>
					</section>
				</params>';
				*/
			break;
			case 'login':
				$results=$myDb->select($requestdata, 'users');
//echo $result;
				if($results){
					$user=$results[0];
					$user['sessionip']=$_SERVER['REMOTE_ADDR'];
					$user['sessionid']=bin2hex(openssl_random_pseudo_bytes(15));
					$user['sessiontimestamp']=date('Y-m-d G:i:s');//2014-12-21 21:09:10
//print_r($user);					
					//save the new data
					$conditions = new stdClass;
					$conditions->id=$user['id'];
					$myDb->update($user, 'users', $conditions);
					
					//xml
					$login =  $reply->addChild('section');
					$login->addAttribute('name','login');
					
					$sessionid = $login->addChild('attstr');
					$sessionid->addAttribute('name', 'sessionid');
					$sessionid->addAttribute('val', $user['sessionid']);
					
					$id = $login->addChild('attnum');
					$id->addAttribute('name', 'id');
					$id->addAttribute('val', $user['id']);
					
					//xml messages
					$messagges =  $reply->addChild('section');
					$messagges->addAttribute('name','messages');

					$number =  $messagges->addChild('attnum');
					$number->addAttribute('name','number');
					$number->addAttribute('val', 1);

					$msg0 =  $messagges->addChild('attstr');
					$msg0->addAttribute('name','message0');	
					$msg0->addAttribute('val',"Succesfull logged in as\n\n".$user['username']);						
					/*
					echo '<?xml version="1.0" encoding="UTF-8"?>
					<params name="webServerReply">
						<section name="content">
							<attnum name="webServerVersion" val="0.1"/>
							<attstr name="type" val="races"/>
							<attnum name="date" val="12324564"/>
							<attstr name="error" val=""/>
							<section name="reply">
								<section name="login">
									<attstr name="sessionid" val="'.$user['sessionid'].'"/>
									<attnum name="id" val="'. $user['id'].'"/>
								</section>
							</section>
						</section>
					</params>';
					* 
					<?xml version="1.0" encoding="UTF-8"?>
					<params name="webServerReply">
					  <section name="content">
						<attnum name="webServerVersion" val="1"/>
						<attnum name="date" val="1430672345"/>
						<attstr name="error" val="this is an error"/>
						<reply>
						  <section name="login">
							<attstr name="sessionid" val="ea6a300712d54b0db07093395ba962"/>
							<attnum name="id" val="2"/>
						  </section>
						  <section name="messages">
							<attnum name="number" val="1"/>
							<attstr name="message0" val="Succesfull logged in!"/>
						  </section>
						</reply>
					  </section>
					</params>


					*/
				}else{
					//xml messages
					$messagges =  $reply->addChild('section');
					$messagges->addAttribute('name','messages');

					$number =  $messagges->addChild('attnum');
					$number->addAttribute('name','number');
					$number->addAttribute('val', 1);

					$msg0 =  $messagges->addChild('attstr');
					$msg0->addAttribute('name','message0');	
					$msg0->addAttribute('val',"FAILED to login in as\n\n".$requestdata->username."\n\nWrong username or password");							
				}
			//echo $result;

			break;
		}
	}
}

//output the xml as string
$domxml = new DOMDocument('1.0');
$domxml->preserveWhiteSpace = false;
$domxml->formatOutput = true;
$domxml->loadXML($xmlreply->asXML());
echo $domxml->saveXML();
?>
