<h2>Использование DI контейнера:</h2>
<p>Интерфейс</p>
<?php
interface WowInterface
{
    public function getWow(int $wow);
}
?>
<p>Классы, имплементирующие интерфейс</p>
<?php
class BadClass implements WowInterface
{
    public function getWow(int $wow): int
    {
        return 100 + $wow;
    }
}
?>
<?php
class BestClass implements WowInterface
{
    private BadInterface $wow;
    public function __construct(BadInterface $wow) {
        $this->wow = $wow;
    }
    public function getWow(int $wow): int
    {
        return $wow;
    }
}
?>
<p>Класс, у которого в зависимости интерфейс</p>
<?php
class RealityService
{
    private WowInterface $reality;
    public function __construct(WowInterface $reality)
    {
        $this->reality = $reality;
    }
    public function getReality(int $wow): void
    {
        echo $this->reality->getWow($wow);
    }
}
?>
<p>Реализация зависимостей</p>
<?php
$di = new DI();
$itemReality = $di->get(
    RealityService::class, [
        WowInterface::class => BadClass::class
    ]);
$itemReality->getReality(500);
?>
