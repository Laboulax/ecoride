<?php

require_once 'code/controllers/MainController.php';

class CreditsController extends MainController
{

    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }
    public function handle()
    {

        $this->logOut();

        $this->genViews();

        require_once 'code/views/credits.php';
        require_once 'code/views/footer.php';
    }
}
