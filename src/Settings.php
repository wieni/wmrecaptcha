<?php

namespace Drupal\wmrecaptcha;

use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Settings implements SettingsInterface
{
    use ContainerAwareTrait;

    /** @var ConfigFactoryInterface */
    protected $configFactory;

    public function setConfigFactory(ConfigFactoryInterface $configFactory)
    {
        $this->configFactory = $configFactory;

        return $this;
    }

    public function get(string $key)
    {
        if ($this->container->hasParameter("wmrecaptcha.${$key}")) {
            return $this->container->getParameter("wmrecaptcha.${$key}");
        }

        if (isset($this->configFactory)) {
            return $this->configFactory
                ->get('wmrecaptcha.settings')
                ->get($key);
        }

        return null;
    }
}
