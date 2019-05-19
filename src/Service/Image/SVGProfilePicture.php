<?php

namespace App\Service\Image;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use DateTime;
use Psr\Container\ContainerInterface;

/**
 * Class SVGProfilePicture
 */
class SVGProfilePicture
{
    /**
     * @var array
     */
    private $gradients;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * SVGProfilePicture constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->gradients = [
            [
                'start' => '#20bf55',
                'start_percentage' => '0%',
                'end' => '#01baef',
                'end_percentage' => '74%',
                'rotation' => '314',
            ],
            [
                'start' => '#63a4ff',
                'start_percentage' => '0%',
                'end' => '#83eaf1',
                'end_percentage' => '74%',
                'rotation' => '315',
            ],
            [
                'start' => '#89D4CF',
                'start_percentage' => '0%',
                'end' => '#734AE8',
                'end_percentage' => '74%',
                'rotation' => '315',
            ],
            [
                'start' => '#36096D',
                'start_percentage' => '0%',
                'end' => '#37D5D6',
                'end_percentage' => '74%',
                'rotation' => '315',
            ],
            [
                'start' => '#48C3EB',
                'start_percentage' => '0%',
                'end' => '#718EDD',
                'end_percentage' => '74%',
                'rotation' => '315',
            ],
        ];
    }

    /**
     * Generate a profile picture
     *
     * @return string
     */
    public function generate()
    {
        $lenght = count($this->gradients);
        $gradient = $this->gradients[rand(0, $lenght - 1)];
        $picture = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="400" viewBox="0 0 24 24">
    <defs>
        <linearGradient id="background" x1="0" x2="0" y1="0" y2="1" gradientTransform="rotate({$gradient['rotation']})">
            <stop offset="{$gradient['start_percentage']}" stop-color="{$gradient['start']}"/>
            <stop offset="{$gradient['end_percentage']}" stop-color="{$gradient['end']}"/>
        </linearGradient>
        <style type="text/css"><![CDATA[
        #rect1 { fill: url(#background); }
        .stop1 { stop-color: {$gradient['start']}; }
        .stop2 { stop-color: {$gradient['end']}; }
      ]]></style>
    </defs>
    <path fill="none" d="M0 0h24v24H0z"/>
    <path fill="url(#background)" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
</svg>
SVG;
        $now = new DateTime('now', new \DateTimeZone('europe/zurich'));
        $dir = $now->format('Y/m/d');
        $pictureDir = $this->container->get("service_container")->getParameter("upload_directory") . "/{$dir}/";
        if (!is_dir($pictureDir)) {
            mkdir($pictureDir, 0775, true);
        }

        $profilePictureFile = uniqid('', true) . '.svg';
        $profilePicture = $pictureDir . $profilePictureFile;
        $saved = file_put_contents($profilePicture, $picture);
        if (!$saved) {
            throw new ValidationException("Profile picture could not be saved");
        }

        return $profilePicture;
    }
}
