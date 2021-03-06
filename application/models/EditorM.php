<?php

class EditorM extends CI_Model
{
    public function get_sentences($uId, $pId){
        /*
         * Getting Translated sentences, Source sentences, Skipped sentences from database
         */
        $sql = "SELECT * FROM sentences WHERE projectId='$pId' ORDER BY sId ASC";
        $sentences = $this->db->query($sql);
        $sql = "SELECT * FROM list_of_skip WHERE uId='$uId' AND projectId='$pId' ORDER BY sId ASC";
        $skips = $this->db->query($sql);
        $sql = "SELECT * FROM translated WHERE projectId='$pId' ORDER BY sId ASC";
        $translated = $this->db->query($sql);
        $data = array(
            'sentence' => $sentences,
            'skip' => $skips,
            'translated' => $translated
        );
        return $data;
    }
    public function get_raw_meaning($word){
        //getting exact word meaning from database
        $link = mysqli_connect('127.0.0.1','root','','amader');
        $link->set_charset('utf8'); //reading unicode data
        $result = mysqli_query($link,"SELECT * FROM glossary WHERE bnBD like '$word'");
        //$result = mysqli_fetch_assoc($result);
        return $result;
    }
    public function get_meaning($word){
        //getting word meaning which is starts with the given word
        $link = mysqli_connect('127.0.0.1','root','','amader');
        $link->set_charset('utf8');// reading unicode data
        $result = mysqli_query($link,"SELECT * FROM glossary WHERE bnBD like '$word%%'");
        //$result = mysqli_fetch_assoc($result);
        return $result;
    }
    public function skip_sentence($data){
        /*
         * add new row in in skip table when a user skip a sentence
         */
        $sql = "INSERT INTO list_of_skip VALUES ('','".$data['sentence_id']."','".$data['user_id']."','".$data['project_id']."')";
        $result = $this->db->query($sql);
        return $result;
    }
    public function translate_sentence($data){
        /*
         * add new data to translated table when a user translate a sentence
         */
        $sql = "INSERT INTO translated VALUES ('','".$data['sentence_id']."','".$data['user_id']."','".$data['project_id']."','".
            $data['target_text']."','".$data['creation']."')";
        $result = $this->db->query($sql);
        return $result;
    }
    public function get_translated_sentences($word){
        $sql = "SELECT * FROM sentences, translated WHERE sentences.sId=translated.sId AND sourceSentence like '%% $word%%'";
        $result = $this->db->query($sql);
        return $result;
    }
    public function get_tm_sentences(){ //get_translation_memory_sentences
        // select all the translated sentences
        $sql = "SELECT * FROM sentences, translated WHERE sentences.sId=translated.sId";
        $result = $this->db->query($sql);
        return $result;
    }
}