Integration of [gumlet/php-image-resize] and [cropperjs] to Nette Froms

   [gumlet/php-image-resize]: <https://github.com/gumlet/php-image-resize>
   [cropperjs]: <https://fengyuanchen.github.io/cropperjs/>

# How it looks like?

![](https://i.imgur.com/xxOUXAm.jpg)
**Example of usage**
Library can be use as Nette control for picking / croping image forexample for profile picture and etc. 
github flavoured markdown - e.g.


```php
<?php  
  
  
namespace App\Model\Components\UserForm;  
  
use Gumlet\ImageResize;  
use MS\Entity\UserEntity;  
use MS\Forms\BaseForm;  
  
  
class UserForm extends BaseForm {  
  public $id;  
  
  /** Your constructor etc... */  
  
  public function defineForm() {  
  $this->addImage('profile_picture','Zvolte obrázek',$this->imageService->getRealPathsFromId($user->profile_picture)->low)  
 ->setSize(500,500)  
 ->setScaleMode(1,1)  
 ->ignoreAscpectRatioWhileValidate() ->setThumbnailRatio(0.3)  
 ->setLabel('ss');  
  $this->addSubmit('submit');  
 }  
  public function save($values) {  
  /** @var UserEntity $user */  
  $user = $this->userMapper->loadEntityById($this->id);  
  /** Other stuff */  
 //Check if picture is ok  
 if($values->profile_picture instanceof ImageResize) {  
  //Save picture  
  //$values->profile_picture is [gumlet/php-image-resize] see library for more info
  $user->profile_picture = $this->imageService->saveProfilePicture($values->profile_picture);  
 }  //Do other stuff like updateing database
  $this->userMapper->updateEntity($user);  
 }}
```

# How to install? 
You need to add javascript file to your app 

    <link rel="stylesheet" href="cropper.min.all.js">
File path is `img-cropper/dist/cropper.min.all.js`
You also need bootstrap and jQuery. 

I recommend to also add this to your BaseForm 

  ```php
protected function addImage($name, $label ='',$value = '') {  
  return $this[$name] = (new ImageControl($value,$label));  
}
```
Now you are ready to use 
  ```php
  $this->addImage();
  ```
  
   # Dont use nette? 
   its possible to use it just with normal php. Look at example.php
  
