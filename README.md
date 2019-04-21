# How it looks like?


![](https://i.imgur.com/xxOUXAm.jpg)
**Example of usage**
Library can be use as Nette control for picking image. 
    
I want to include code in a  [markdown gist on github](https://gist.github.com/derekdreery/8933123), and cannot work out how to do syntax highlighting.

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
  //$values->profile_picture is is Gumlet/ImageResize see library for more info
  $user->profile_picture = $this->imageService->saveProfilePicture($values->profile_picture);  
 }  //Do other stuff like updateing database
  $this->userMapper->updateEntity($user);  
 }}
```

# How to install? 
You need to add javascript file to your app 

    <link rel="stylesheet" href="cropper.min.css">
File path is `img-cropper/dist/cropper.min.css`

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
  
