<?php
/**
 *
 * @author pes2704
 */
interface Framework_Document_DocumentInterface {
    public function getContent();
    public function includeDocument(Framework_Document_DocumentInterface $mergedDocument, $slot="");
}
