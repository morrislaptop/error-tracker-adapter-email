<?php  namespace Morrislaptop\ErrorTracker\Provider\Email;

use Exception;
use League\CommonMark\CommonMarkConverter;
use RuntimeException;
use SplFileObject;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class Body {

    /**
     * @var VarCloner
     */
    protected $cloner;

    /**
     * @var CliDumper
     */
    protected $dumper;

    /**
     * @param VarCloner $cloner
     * @param CliDumper $dumper
     */
    public function __construct(VarCloner $cloner, CliDumper $dumper)
    {
        $this->cloner = $cloner;
        $this->dumper = $dumper;
    }

    /**
     * @param Exception $e
     * @param array $extra
     * @return string
     */
    public function build(Exception $e, array $extra) {
        ob_start();
        include __DIR__ . '/body.tpl.php';
        return ob_get_clean();
    }

    /**
     * @param $variable
     * @return resource|string
     * @throws Exception
     * @throws null
     */
    protected function dump($variable)
    {
        $output = fopen('php://memory', 'r+b');
        $this->dumper->dump($this->cloner->cloneVar($variable), $output);
        rewind($output);
        $output = stream_get_contents($output);

        return $output;
    }

    protected function getCode($path, $line, $numLines)
    {
        if (empty($path) || empty($line) || !file_exists($path)) {
            return NULL;
        }

        try {
            // Get the number of lines in the file
            $file = new SplFileObject($path);
            $file->seek(PHP_INT_MAX);
            $totalLines = $file->key() + 1;

            // Work out which lines we should fetch
            $start = max($line - floor($numLines / 2), 1);
            $end = $start + ($numLines - 1);
            if ($end > $totalLines) {
                $end = $totalLines;
                $start = max($end - ($numLines - 1), 1);
            }

            // Get the code for this range
            $code = array();

            $file->seek($start - 1);
            while ($file->key() < $end) {
                $code[$file->key() + 1] = rtrim(substr($file->current(), 0, 200));
                $file->next();
            }

            return $code;
        } catch (RuntimeException $ex) {
            return null;
        }
    }
}