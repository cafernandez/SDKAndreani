<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Andreani\Andreani;
use Andreani\Requests\CotizarEnvio;

class DefaultController extends Controller {

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/cotizador", name="cotizador")
     */
    public function cotizadorAction(Request $request) {
        $cotizacion = new CotizarEnvio();
        $cotizacion
                ->setCodigoDeCliente('CL0003750')
                ->setNumeroDeContrato('400006709')
                ->setCodigoPostal('1014')
                ->setPeso(500)
                ->setVolumen(100)
                ->setValorDeclarado(100);

        $andreani = new Andreani('eCommerce_Integra', 'passw0rd', 'test');
        $response = $andreani->call($cotizacion);
        if ($response->isValid()) {
            $tarifa = $response->getMessage()->CotizarEnvioResult->Tarifa;
            echo "La cotización funcionó bien y la tarifa es $tarifa";
        } else {
            echo "La cotización falló, el mensaje de error es el siguiente";
            var_dump($response->getMessage());
        }
        die;
    }

}
