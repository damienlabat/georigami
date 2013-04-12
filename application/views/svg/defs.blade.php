    <defs>
      <linearGradient id="preview" x1="0%" x2="0%" y1="0" y2="100%">
        <stop offset="20%"    stop-color="#fff" stop-opacity="1" />
        <stop offset="80%"  stop-color="#aaa" stop-opacity="1" />
      </linearGradient>
    </defs>

    <defs>
      <linearGradient id="greygradient" x1="0%" x2="0%" y1="0" y2="{{-$max*$svg_vscale}}" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#aaa" stop-opacity="1" />
        <stop offset="100%" stop-color="#fff" stop-opacity="1" />
      </linearGradient>
    </defs>

    <defs>
      <linearGradient id="fillstyle2" x1="0%" x2="0%" y1="0" y2="{{-$max*$svg_vscale}}" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#000" stop-opacity="1" />
        <stop offset="25%"  stop-color="#1C1191"  stop-opacity="1" />
        <stop offset="50%"  stop-color="#E90B0C"  stop-opacity="1" />
        <stop offset="75%"   stop-color="#FF8600"  stop-opacity="1" />
        <stop offset="100%"   stop-color="#EFE935"  stop-opacity="1" />
      </linearGradient>
    </defs>

    <defs>
      <linearGradient id="fillstyle3" x1="0%" x2="0%" y1="0" y2="{{-$max*$svg_vscale}}" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#E1645D" stop-opacity="1" />
        <stop offset="50%"  stop-color="#FFFFCF"  stop-opacity="1" />
        <stop offset="100%"   stop-color="#54B27C"  stop-opacity="1" />
      </linearGradient>
    </defs>

      <defs>
      <linearGradient id="fillstyle4" x1="0%" x2="0%" y1="0" y2="{{-$max*$svg_vscale}}" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#45836C" stop-opacity="1" />
        <stop offset="25%" stop-color="#45836C" stop-opacity="1" />
        <stop offset="50%"  stop-color="#D1D0A4"  stop-opacity="1" />
        <stop offset="75%"   stop-color="#C4B08C"  stop-opacity="1" />
        <stop offset="100%"   stop-color="#fff"  stop-opacity="1" />
      </linearGradient>
    </defs>


    <!-- from -10000m to 10000m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (-10000-$altimin)*$unitpermeter;
        $y2= -1 * (10000-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="preview2" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#0B596C" stop-opacity="1" /> <!-- -10000m -->
        <stop offset="50%"  stop-color="#84E7FF"  stop-opacity="1" /> <!-- 0m -->
        <stop offset="50%"  stop-color="#70D926"  stop-opacity="1" /> <!-- 0m -->
        <stop offset="55%"  stop-color="#B7906A"  stop-opacity="1" /> <!-- 1000m -->
        <stop offset="65%"  stop-color="#C49A63"  stop-opacity="1" /> <!-- +3000m -->
        <stop offset="75%"  stop-color="#E7E7E7"  stop-opacity="1" /> <!-- +5000m -->
        <stop offset="100%"   stop-color="#ffffff"  stop-opacity="1" /> <!-- +10000m -->
      </linearGradient>
    </defs>

    <defs>
      <linearGradient id="realtopo" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#002D9F" stop-opacity="1" /> <!-- -10000m -->
        <stop offset="25%" stop-color="#3466E3" stop-opacity="1" /> <!-- -5000m -->
        <stop offset="50%"  stop-color="#D3E4E3"  stop-opacity="1" /> <!-- 0m -->
        <stop offset="50%"  stop-color="#66C58B"  stop-opacity="1" /> <!-- 0m -->
        <stop offset="55%"  stop-color="#E7E4BE"  stop-opacity="1" /> <!-- +1000m -->
        <stop offset="65%"  stop-color="#C49A63"  stop-opacity="1" /> <!-- +3000m -->
        <stop offset="75%"  stop-color="#E7E7E7"  stop-opacity="1" /> <!-- +5000m -->
        <stop offset="100%"   stop-color="#EEEEEE"  stop-opacity="1" /> <!-- +10000m -->
      </linearGradient>
    </defs>

    <defs>
      <linearGradient id="realtopo2" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#002D9F" stop-opacity="1" /> <!-- -10000m -->
        <stop offset="45%" stop-color="#3466E3" stop-opacity="1" /> <!-- -1000m -->
        <stop offset="50%"  stop-color="#D3E4E3"  stop-opacity="1" /> <!-- 0m -->
        <stop offset="50%"  stop-color="#66C58B"  stop-opacity="1" /> <!-- 0m -->
        <stop offset="52.5%"  stop-color="#E7E4BE"  stop-opacity="1" /> <!-- +500m -->
        <stop offset="55%"  stop-color="#C49A63"  stop-opacity="1" /> <!-- +1000m -->
        <stop offset="65%"  stop-color="#E7E7E7"  stop-opacity="1" /> <!-- +3000m -->
        <stop offset="100%"   stop-color="#EEEEEE"  stop-opacity="1" /> <!-- +10000m -->
      </linearGradient>
    </defs>


    <!-- bands  -->

    <?php
    $c1='#669933';
    $c2='#9eed4e';
    ?>

    <!-- from 0m to 2000m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (0-$altimin)*$unitpermeter;
        $y2= -1 * (2000-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="bands1000" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse" spreadMethod="repeat">
        <stop offset="0%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="50%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="50%" stop-color="<?=$c2?>" stop-opacity="1" />
        <stop offset="100%" stop-color="<?=$c2?>" stop-opacity="1" />
      </linearGradient>
    </defs>

    <!-- from 0m to 200m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (0-$altimin)*$unitpermeter;
        $y2= -1 * (200-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="bands100" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse" spreadMethod="repeat">
        <stop offset="0%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="50%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="50%" stop-color="<?=$c2?>" stop-opacity="1" />
        <stop offset="100%" stop-color="<?=$c2?>" stop-opacity="1" />
      </linearGradient>
    </defs>


    <!-- from 0m to 20m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (0-$altimin)*$unitpermeter;
        $y2= -1 * (20-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="bands10" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse" spreadMethod="repeat">
        <stop offset="0%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="50%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="50%" stop-color="<?=$c2?>" stop-opacity="1" />
        <stop offset="100%" stop-color="<?=$c2?>" stop-opacity="1" />
      </linearGradient>
    </defs>


 <!-- bands2  -->

    <?php
    $c1='#669933';
    $c2='#9eed4e';
    ?>

    <!-- from 0m to 1000m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (0-$altimin)*$unitpermeter;
        $y2= -1 * (1000-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="bands1000b" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse" spreadMethod="reflect">
        <stop offset="0%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="100%" stop-color="<?=$c2?>" stop-opacity="1" />
      </linearGradient>
    </defs>

    <!-- from 0m to 100m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (0-$altimin)*$unitpermeter;
        $y2= -1 * (100-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="bands100b" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse" spreadMethod="reflect">
        <stop offset="0%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="100%" stop-color="<?=$c2?>" stop-opacity="1" />
      </linearGradient>
    </defs>


    <!-- from 0m to 20m -->
    <?php
        $unitpermeter= ($max*$svg_vscale)/($altimax-$altimin);
        $y1= -1 * (0-$altimin)*$unitpermeter;
        $y2= -1 * (10-$altimin)*$unitpermeter;
    ?>

    <defs>
      <linearGradient id="bands10b" x1="0%" x2="0%" y1="<?=$y1?>" y2="<?=$y2?>" gradientUnits="userSpaceOnUse" spreadMethod="reflect">
        <stop offset="0%" stop-color="<?=$c1?>" stop-opacity="1" />
        <stop offset="100%" stop-color="<?=$c2?>" stop-opacity="1" />
      </linearGradient>
    </defs>
