<h2>Использование DI контейнера:</h2>

<b>Интерфейс</b>

<pre>
interface WowInterface
{
    public function getWow(int $wow);
}
</pre>

<b>Классы, имплементирующие интерфейс</b>

<pre>
class BadClass implements WowInterface
{
    public function getWow(int $wow): int
    {
        return 100 + $wow;
    }
}
</pre>

<pre>
class BestClass implements WowInterface
{
    public function getWow(int $wow): int
    {
        return $wow;
    }
}
</pre>

<b>Класс, у которого в зависимости интерфейс</b>

<pre>
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
</pre>

<b>Реализация зависимостей</b>

<pre>
$di = new DI();
$itemReality = $di->get(
    RealityService::class, [
        WowInterface::class => BadClass::class
    ]);
$itemReality->getReality(500);
</pre>
