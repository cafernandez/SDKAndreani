<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Andreani\Andreani;
use Andreani\Requests\CotizarEnvio;
use Andreani\Requests\ConfirmarCompra;
use Andreani\Requests\ConsultarSucursales;

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

    /**
     * @Route("/confirmar-compra", name="confirmar-compra")
     */
    public function confirmarCompraAction(Request $request) {
        $compra = new ConfirmarCompra();
        $compra
                //->setCalle('Santo Domingo')
                //->setCategoriaDistancia($categoriaDistancia)
                //->setCategoriaFacturacion($categoriaFacturacion)
                //->setCategoriaPeso($categoriaPeso)
                //->setCodigoDeSucursal($codigoDeSucursal)
                //->setCodigoPostal(1292)
                ->setDatosTransaccion(400006709, 'ID123', 132, 1500);
        $compra->setDatosEnvio(100, 100, 1000, null, null, null, 'Campera', 'Campera');
        $compra->setDatosDestino('Buenos Aires', 'CABA', 1292, 'Santo Domingo', 3220);
        $compra->setDatosDestinatario('Juana Github', '', 'DNI', '1122233', 'juana@githu.com', '11222333', '44445555');

        $andreani = new Andreani('eCommerce_Integra', 'passw0rd', 'test');
        $response = $andreani->call($compra);
        if ($response->isValid()) {
            $numeroAndreani = $response->getMessage()->ConfirmarCompraResult->NumeroAndreani;
            echo "La compra funcionó bien y su número de seguimiento es $numeroAndreani";
        } else {
            echo "La compra falló, el mensaje de error es el siguiente";
            var_dump($response->getMessage());
        }
        die;
    }

    /**
     * @Route("/sucursales", name="sucursales")
     */
    public function consultarSucursalesAction(Request $request) {
        $sucursales = new ConsultarSucursales();

        $sucursales
                ->setCodigoPostal('1292');
//                ->setLocalidad('C.A.B.A.')
//                ->setProvincia('Buenos Aires');
        
        $andreani = new Andreani('eCommerce_Integra', 'passw0rd', 'test');
        $response = $andreani->call($sucursales);
        if ($response->isValid()) {
            $sucursalesResponse = $response->getMessage()->ConsultarSucursalesResult->ResultadoConsultarSucursales;
            echo "La consulta de sucursales funcionó bien y sus sucursales son: ";
            var_dump($sucursalesResponse);
        } else {
            echo "La consulta de sucursales falló, el mensaje de error es el siguiente";
            var_dump($response->getMessage());
        }
        die;
    }

}
