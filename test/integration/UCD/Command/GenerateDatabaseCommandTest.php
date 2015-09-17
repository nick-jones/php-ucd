<?php

namespace integration\UCD\Command;

use integration\UCD\TestCase as BaseTestCase;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use UCD\Console\Command\GenerateDatabaseCommand;

use VirtualFileSystem\FileSystem;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

class GenerateDatabaseCommandTest extends BaseTestCase
{
    const FILE_CONTENT = <<<XML
<ucd xmlns="http://www.unicode.org/ns/2003/ucd/1.0">
   <repertoire>
      <char cp="0000" age="1.1" na="" JSN="" gc="Cc" ccc="0" dt="none" dm="#" nt="None" nv="NaN" bc="BN" bpt="n" bpb="#"
            Bidi_M="N" bmg="" suc="#" slc="#" stc="#" uc="#" lc="#" tc="#" scf="#" cf="#" jt="U" jg="No_Joining_Group"
            ea="N" lb="CM" sc="Zyyy" scx="Zyyy" Dash="N" WSpace="N" Hyphen="N" QMark="N" Radical="N" Ideo="N" UIdeo="N"
            IDSB="N" IDST="N" hst="NA" DI="N" ODI="N" Alpha="N" OAlpha="N" Upper="N" OUpper="N" Lower="N" OLower="N"
            Math="N" OMath="N" Hex="N" AHex="N" NChar="N" VS="N" Bidi_C="N" Join_C="N" Gr_Base="N" Gr_Ext="N"
            OGr_Ext="N" Gr_Link="N" STerm="N" Ext="N" Term="N" Dia="N" Dep="N" IDS="N" OIDS="N" XIDS="N" IDC="N"
            OIDC="N" XIDC="N" SD="N" LOE="N" Pat_WS="N" Pat_Syn="N" GCB="CN" WB="XX" SB="XX" CE="N" Comp_Ex="N"
            NFC_QC="Y" NFD_QC="Y" NFKC_QC="Y" NFKD_QC="Y" XO_NFC="N" XO_NFD="N" XO_NFKC="N" XO_NFKD="N" FC_NFKC="#"
            CI="N" Cased="N" CWCF="N" CWCM="N" CWKCF="N" CWL="N" CWT="N" CWU="N" NFKC_CF="#" InSC="Other" InPC="NA"
            blk="ASCII" isc="" na1="NULL">
         <name-alias alias="NUL" type="abbreviation"/>
         <name-alias alias="NULL" type="control"/>
      </char>
   </repertoire>
</ucd>
XML;

    /**
     * @var CommandTester
     */
    protected $commandTester;

    /**
     * @var FileSystem
     */
    protected $fs;

    /**
     * @var string
     */
    protected $dbPath;

    /**
     * @var string
     */
    protected $ucdXmlPath;

    protected function setUp()
    {
        $application = new Application();
        $application->add(new GenerateDatabaseCommand());
        $command = $application->get('generate-database');
        $this->commandTester = new CommandTester($command);

        $this->fs = new FileSystem();
        $this->dbPath = $this->fs->path('/db/');
        $this->ucdXmlPath = $this->fs->path('/ucd.xml');

        file_put_contents($this->ucdXmlPath, self::FILE_CONTENT);
    }

    /**
     * @test
     */
    public function it_can_generate_a_file_database()
    {
        $this->commandTester->execute([
            'command' => GenerateDatabaseCommand::COMMAND_NAME,
            '--db-location' => $this->dbPath,
            'ucdxml-location' => $this->ucdXmlPath
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Database Generated'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));

        $files = $this->getDbFiles();

        ha::assertThat('files', $files, hm::is(hm::arrayWithSize(1)));
        ha::assertThat('file name', $files[0]->getBasename(), hm::is(hm::identicalTo('00000000-01114111!0001.php')));
        ha::assertThat('file size', $files[0]->getSize(), hm::is(hm::greaterThan(0)));

        $data = require (string)$files[0];

        ha::assertThat('dumped data', $data, hm::is(hm::arrayWithSize(1)));
        ha::assertThat('dumped data', $data, hm::hasKeyInArray(0));
    }

    /**
     * @test
     */
    public function it_can_display_debug_information()
    {
        $this->commandTester->execute([
            'command' => GenerateDatabaseCommand::COMMAND_NAME,
            '--debug' => true,
            'ucdxml-location' => $this->ucdXmlPath
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Database Not Generated'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));
    }

    /**
     * @return \SplFileInfo[]
     */
    protected function getDbFiles()
    {
        $directory = new \FilesystemIterator($this->dbPath, \FilesystemIterator::CURRENT_AS_FILEINFO);

        $files = new \CallbackFilterIterator($directory, function (\SplFileInfo $file) {
            return $file->isFile();
        });

        return iterator_to_array($files, false);
    }
}