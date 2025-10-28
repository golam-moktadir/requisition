<?php

namespace App\Library;

class DatatableExporter
{
    /**
     * The single instance of the LoggerSingleton class.
     *
     * @var LoggerSingleton
     */
    private static $instance = null;

    /**
     * Private constructor to prevent instance creation outside of the class.
     */
    private function __construct()
    {
        // Initialization code (e.g., create a log file, set up the logger, etc.)
        // You can customize this as needed.
        // echo "Logger initialized\n";
    }

    /**
     * Prevent the object from being cloned.
     */
    private function __clone() {}

    /**
     * Prevent the object from being unserialized.
     */
    private function __wakeup() {}

    /**
     * Returns the single instance of the class.
     *
     * @return DatatableExporter Singleton
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new DatatableExporter();
        }

        return self::$instance;
    }

    /**
     * Example method to log messages.
     *
     * @param string $message
     * @return void
     */
    public function log($message)
    {
        echo "Log: " . $message . "\n";
    }

    public function exportCsv($filename, $headers, $data=[])
    {
        $filename = $filename . '_' . date('ymdHis') . '_' . mt_rand();
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'. $filename .'.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);         
        foreach ($data as $row) {
            // fputcsv($output, $row);
            $inputs = array();
            foreach ($headers as $name => $title) {
                $inputs[] = $row[$name];
            }
            fputcsv($output, $inputs);
        }
        fclose($output);    
        return true;    
    }
}
