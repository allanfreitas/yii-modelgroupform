<?php
/**
 * ModelGroupForm.php class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Form model that can handle other models in a massive way.
 */
class ModelGroupForm extends CFormModel 
{
	/**
	 * @var CModel[] $models the model instances (name=>model).
	 */
	public $models = array();

	/**
	 * Does magic for getting attributes within in group models.
	 * @param string $name the property name or event name
	 * @return mixed the property value, event handlers attached to the event, or the named behavior
	 * @throws CException if the property or event is not defined
	 */
	public function __get($name) 
	{
		$name = $this->resolveModelAttribute($name);
		if (is_array($name)) 
		{
			list($modelName, $attributeName) = $name;
			if (isset($this->models[$modelName]))
				return $this->models[$modelName]->{$attributeName};
		}
		return parent::__get($name);
	}

	/**
	 * Sets the attribute values in a massive way.
	 * @param array $values attribute values (name=>value) to be set.
	 * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
	 */
	public function setAttributes($values, $safeOnly = true) 
	{
		parent::setAttributes($values, $safeOnly);
		foreach ($values as $name => $value) 
		{
			$name = $this->resolveModelAttribute($name);
			if (is_array($name)) 
			{
				list($modelName, $attributeName) = $name;
				if (isset($this->models[$modelName]))
					$this->models[$modelName]->{$attributeName} = $value;
			}
		}
	}

	/**
	 * Performs the validation.
	 * @param array $attributes list of attributes that should be validated. Defaults to null,
	 * @param boolean $clearErrors whether to call {@link clearErrors} before performing validation
	 * @return boolean whether the validation is successful without any error.
	 */
	public function validate($attributes = null, $clearErrors = true) 
	{
		$valid = parent::validate($attributes, $clearErrors);
		foreach ($this->models as $model)
			$valid = $model->validate(null, $clearErrors) && $valid; // only valid if all models are valid
		return $valid;
	}

	/**
	 * Returns the text label for the specified attribute.
	 * @param string $attribute the attribute name
	 * @return string the attribute label
	 */
	public function getAttributeLabel($attribute) 
	{
		$attribute = $this->resolveModelAttribute($attribute);
		if (is_array($attribute)) 
		{
			list($modelName, $attributeName) = $attribute;
			if (isset($this->models[$modelName]))
				return $this->models[$modelName]->getAttributeLabel($attributeName);
		} 
		return parent::getAttributeLabel($attribute);
	}

	/**
	 * Returns a value indicating whether there is any validation error.
	 * @param string $attribute attribute name. Use null to check all attributes.
	 * @return boolean whether there is any error.
	 */
	public function hasErrors($attribute = null) 
	{
		$attribute = $this->resolveModelAttribute($attribute);
		if (is_array($attribute)) 
		{
			list($modelName, $attributeName) = $attribute;
			if (isset($this->models[$modelName]))
				return $this->models[$modelName]->hasErrors($attributeName);
		}
		return parent::hasErrors($attribute);
	}

	/**
	 * Returns the errors for all attribute or a single attribute.
	 * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
	 * @return array errors for all attributes or the specified attribute. Empty array is returned if no error.
	 */
	public function getErrors($attribute = null) 
	{
		$errors = parent::getErrors($attribute);
		foreach ($this->models as $model)
			$errors = array_merge($errors, $model->getErrors($attribute));
		return $errors;
	}

	/**
	 * Returns the first error of the specified attribute.
	 * @param string $attribute attribute name.
	 * @return string the error message. Null is returned if no error.
	 */
	public function getError($attribute) 
	{
		$attribute = $this->resolveModelAttribute($attribute);
		if (is_array($attribute)) 
		{
			list($modelName, $attributeName) = $attribute;
			if (isset($this->models[$modelName]))
				return $this->models[$modelName]->getError($attributeName);
		}
		return parent::getError($attribute);
	}

	/**
	 * Turns model.attribute into array($model, $attribute)
	 * @param $attribute the attribute name.
	 * @return string|array the attribute and model if applicable.
	 */
	public function resolveModelAttribute($attribute) 
	{
		return strpos($attribute, '.') > 0 ? explode('.', $attribute) : $attribute;
	}
}
