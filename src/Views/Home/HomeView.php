<?php
namespace Views\Home;
use Views\Base\BaseView;
class HomeView extends BaseView {
    public function templatePath(): string { return __DIR__ . '/home.php'; }
}
