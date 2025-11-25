<?php
/**
 * HomeController - Main homepage
 */

class HomeController extends BaseController {
    
    public function index() {
        // If user is logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }
        
        $sucursalModel = new Sucursal();
        $servicioModel = new Servicio();
        
        $data = [
            'title' => 'Bienvenido a ' . APP_NAME,
            'sucursales' => $sucursalModel->getAll(),
            'categorias' => $servicioModel->getCategorias()
        ];
        
        $this->view('home/index', $data);
    }
    
    public function notFound() {
        http_response_code(404);
        $data = [
            'title' => 'Página no encontrada - ' . APP_NAME
        ];
        $this->view('home/404', $data);
    }
}
