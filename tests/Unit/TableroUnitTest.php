<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Tablero;

class TableroUnitTest extends TestCase
{
    /**
     * Crea un tablero de 4x4
     *
     * @return void
     */
    public function testCrearTableroMinimoTest()
    {
        $x = 4;
        $y = 4;
        $tableroMinimoEsperado =    "no,no,no|no,no,no|no,no,no|no,no,no|".
                                    "no,no,no|no,no,no|no,no,no|no,no,no|".
                                    "no,no,no|no,no,no|no,no,no|no,no,no|".
                                    "no,no,no|no,no,no|no,no,no|no,no,no|";
        $tableroMinimo = "";
        $tableroMinimo = Tablero::crearTableroMinimo($x,$y);

        $this->assertTrue($tableroMinimoEsperado == $tableroMinimo);
    }

    public function testTransformarTableroMinimoALogicoTest()
    {
        $x = 4;
        $y = 4;
        $tableroMinimo = "01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|01,no,no|";

        $tableroLogico = Tablero::transformarTableroMinimoALogico($tableroMinimo,$x,$y);

        for ( $i = 0; $i < $x ; $i++)
        {
            for ( $j = 0; $j < $y ; $j++)
            {
                $this->assertTrue($tableroLogico[$i][$j]->LadoEstaCompleto == '01');
                $this->assertTrue($tableroLogico[$i][$j]->ArribaEstaCompleto == 'no');
                $this->assertTrue($tableroLogico[$i][$j]->PuntoEstaCompleto == 'no');
            }
        }

    }

    public function testTransformarTableroMinimoALogicoYLogicoAminimoTest()
    {
        $x = 4;
        $y = 4;
        $tableroMinimoOriginal = "01,02,03|04,05,06|07,08,09|10,11,12|13,14,15|16,17,18|19,20,21|22,23,24|25,26,27|28,29,30|31,32,33|34,35,36|37,38,39|40,41,42|43,44,45|46,47,48|";

        $tableroLogico = Tablero::transformarTableroMinimoALogico($tableroMinimoOriginal,$x,$y);

        $tableroLogicoAMinimo = Tablero::transformarTableroLogicoAMinimo($tableroLogico,$x,$y);

        $this->assertTrue($tableroMinimoOriginal == $tableroLogicoAMinimo);



    }

    public function testVerificarCuadro(){
        $x = 4;
        $y = 4;
        $i = 1;
        $j = 1;

        $tableroMinimoCuadroCompleto =  "no,no,no|no,no,no|no,no,no|no,no,no|".
                                        "no,no,no|01,01,no|01,no,no|no,no,no|".
                                        "no,no,no|no,01,no|no,no,no|no,no,no|".
                                        "no,no,no|no,no,no|no,no,no|no,no,no|";

        $tableroMinimoCuadroIncompleto =    "no,no,no|no,no,no|no,no,no|no,no,no|".
                                            "no,no,no|01,01,no|no,01,no|no,no,no|".
                                            "no,01,no|01,no,no|no,no,no|no,no,no|".
                                            "01,01,no|no,01,no|no,no,no|no,no,no|";

        $tableroLogicoCuadroCompleto = Tablero::transformarTableroMinimoALogico($tableroMinimoCuadroCompleto,$x,$y);
        $tableroLogicoCuadroIncompleto = Tablero::transformarTableroMinimoALogico($tableroMinimoCuadroIncompleto,$x,$y);

        $completo = Tablero::VerificarPuntoCompleto($i, $j,$tableroLogicoCuadroCompleto,$x, $y);

        $incompleto = Tablero::VerificarPuntoCompleto($i, $j,$tableroLogicoCuadroIncompleto,$x, $y);

        $this->assertTrue($completo);
        $this->assertTrue(!$incompleto);
        

    }
}