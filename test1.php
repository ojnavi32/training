<?php
namespace Drupal\form_test\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

//test
use Drupal\node\Entity\Node;



class test1 extends ControllerBase {
    public function ShowPage() {

        $form = \Drupal::formBuilder()->getForm( new Form() );
    
        $markup = [
            '#theme' => 'test1', // theme name that will be matched in *.module
            '#title' => 'This is a title coming from test1::showpage',
            '#form' => $form,
        ];

        return $markup;
    }
}

class Form extends FormBase {

    public function getFormId()
    {
        return 'form4';
    }

    public function buildForm( array $form, FormStateInterface $form_state ) {
        $form['email'] = [
            '#type' => 'textfield',
            '#title' => $this->t( 'Input Title' ),
            '#default_value' => \Drupal::state()->get('third.email'),
        ];

        $form['content'] = [
            '#type' => 'textfield',
            '#title' => $this->t( 'Input Content' ),
            '#default_value' => \Drupal::state()->get('third.content'),
        ];

        $form['image'] = [
            '#type' => 'file',
            '#title' => $this->t( 'Input Image' ),
            '#default_value' => \Drupal::state()->get('third.image'),
        ];        

        $form['action']['submit'] = [
            '#type' => 'submit',
            '#value' => 'OKAY. SUBMIT NOW',
            '#prefix' => '<div class="submit-button">',
            '#suffix' => '</div>',
        ];

        return $form;
    }

    public function submitForm( array &$form, FormStateInterface $form_state ) {

    $getfile = file_get_contents("C:/wamp/www/final/modules/form_test/src/Controller/text.txt");

        $str = explode('-- new article --', $getfile);
        $j = 0;
        while ($j < 3) {

        $aa = preg_split('/[\r\n]+/', $str[$j]);

        $new = null;

        foreach( $aa as $a){
            if( $a == "" ) continue;
            $new[] = $a;
        } //foreach loop

                $number =  'Hello there'; // Number
                $title = $new[1]; // Title
                $content =  $new[2]; // Content
                $date =  $new[3]; // Date
                $url = $new[4];  //url
            $p = [];
            $p['type'] = 'article';
            $p['title'] = ''.$title;

            $node = Node::create( $p );
            $node->save();

    $file = file_save_data(file_get_contents($url));
    \Drupal::service('file.usage')->add($file, 'editor', 'node', $node->id());

        $src = $file->url();
        $src = str_replace('http://default', 'http://localhost/final', $src);
        $uuid = $file->uuid();
        $img = "<img src='$src' data-entity-type='file' data-entity-uuid='$uuid'>";

            $node->body->format = 'full_html';
            $node->body->value = '<h1>'.$content.'</h1><br/>'.$date.'<br />'.$img; 

            $node->save();
            $j++;
        }
    }
}