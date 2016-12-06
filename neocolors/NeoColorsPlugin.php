<?php
namespace Craft;

class NeoColorsPlugin extends BasePlugin
{

	private $_neoBlockColors;

	public function init()
	{
		parent::init();
		if (craft()->request->isCpRequest()) {
			$this->_colorBlocks();
		}
	}

	public function getName()
	{
		return Craft::t('Neo Colors');
	}

	public function getDescription()
	{
		return 'Identify your Neo blocks by giving each type a different color.';
	}

	public function getDocumentationUrl()
	{
		return 'https://github.com/louderthanten/craft-neocolors';
	}

	public function getVersion()
	{
		return '1.1.3';
	}

	public function getSchemaVersion()
	{
		return '1.1.0';
	}

	public function getDeveloper()
	{
		return 'Louder Than Ten';
	}

	public function getDeveloperUrl()
	{
		return 'https://github.com/louderthanten/craft-neocolors';
		//return 'http://louderthanten.com';
	}

	protected function defineSettings()
	{
		return array(
			'NeoBlockColors' => array(AttributeType::Mixed, 'label' => 'Neo Block Colors', 'default' => array()),
		);
	}

	public function getSettingsHtml()
	{
		// If not set, create a default row
		if (!$this->_neoBlockColors) {
			$this->_neoBlockColors = array(array('blockType' => '', 'backgroundColor' => ''));
		}
		// Generate table
		$NeoBlockColorsTable = craft()->templates->renderMacro('_includes/forms', 'editableTableField', array(
			array(
				'label'        => Craft::t('Block Type Colors'),
				'instructions' => Craft::t('Add background colors to your Neo block types'),
				'id'           => 'neoBlockColors',
				'name'         => 'neoBlockColors',
				'cols'         => array(
					'blockType' => array(
						'heading' => Craft::t('Block Type Handle'),
						'type'    => 'singleline',
					),
					'backgroundColor' => array(
						'heading' => Craft::t('CSS Background Color'),
						'type'    => 'singleline',
						'class'   => 'code',
					),
				),
				'rows' => $this->_NeoBlockColors,
				'addRowLabel'  => Craft::t('Add a block type color'),
			)
		));
		// Settings JS
		craft()->templates->includeJsResource('neocolors/js/settings.js');
		// Output settings template
		return craft()->templates->render('neocolors/_settings', array(
			'neoBlockColorsTable' => TemplateHelper::getRaw($neoBlockColorsTable),
		));
	}

	private function _colorBlocks()
	{
		$this->_neoBlockColors = $this->getSettings()->neoBlockColors;
		$css = '';
		$colorList = array();
		// Loop through block colors
		if ($this->_neoBlockColors) {
			foreach ($this->_neoBlockColors as $row) {
				// Set color
				$color = $row['backgroundColor'];
				// Split comma-separated strings
				$types = explode(',', $row['blockType']);
				// Loop over each block type
				foreach ($types as $type) {
					$type = trim($type);
					// Ignore empty strings
					if (empty($type)) {
						continue;
					}
					// Add type to color list
					$colorList[] = $type;
					// Set CSS for type
					$css .= "
.mc-solid-{$type} {background-color: {$color};}
.btngroup .btn.mc-gradient-{$type} {background-image: linear-gradient(white,{$color});}";
				}
			}
			// Load CSS
			craft()->templates->includeCss($css);
		}
		// Load JS
		craft()->templates->includeJs('var colorList = '.json_encode($colorList).';');
		craft()->templates->includeJsResource('neocolors/js/Neocolors.js');
	}

}
