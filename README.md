ModelGroupForm
==============

A form model that can handle other models in massive way.

## Usage

You can either use this model directly or extend it. Here's an example on its usage:

**ExampleController.php**
```php
// Create some models.
$user = new User;
$profile = new Profile;
$workAddress = new Address;
$homeAddress = new Address;

// Create the model group form instance.
$model = new ModelGroupForm;

// Assign the models to the form model.
$model->models = array(
	'user' => $user,
	'profile' => $profile,
	'homeAddress' => $homeAddress,
	'workAddress' => $workAddress,
);

// Check whether the form is submitted.
if (isset($_POST['ModelGroupFrom'])) // post key = model name
{
	// Magically assign all attributes to their models.
	$model->attributes = $_POST['ModelGroupForm'];

	// Validate all models at once.
	if ($model->validate()) {
		// Model is valid, do what you please.
	}
}

$this->render('example-view', array('model' => $model));

```

**example-view.php**
```php
<?php $form = $this->widget('CActiveForm'); ?>

<!-- user model fields -->
<?php echo $form->textField($model, 'user.name'); ?>
<?php echo $form->passwordField($model, 'user.password'); ?>

<!-- profile model fields -->
<?php echo $form->textField($model, 'profile.firstName'); ?>
<?php echo $form->textField($model, 'profile.lastName'); ?>

<!-- homeAddress model fields -->
<?php echo $form->textField($model, 'homeAddress.street'); ?>
<?php echo $form->textField($model, 'homeAddress.city'); ?>
<?php echo $form->textField($model, 'homeAddress.postCode'); ?>
<?php echo $form->textField($model, 'homeAddress.country'); ?>

<!-- workAddress model fields -->
<?php echo $form->textField($model, 'workAddress.street'); ?>
<?php echo $form->textField($model, 'workAddress.city'); ?>
<?php echo $form->textField($model, 'workAddress.postCode'); ?>
<?php echo $form->textField($model, 'workAddress.country'); ?>

<?php echo CHtml::submitButton('Submit'); ?>

<?php $this->endWidget(); ?>
```

And that's all that is to it.