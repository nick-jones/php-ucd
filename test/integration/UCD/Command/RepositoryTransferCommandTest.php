<?php

namespace integration\UCD\Command;

use integration\UCD\TestCase as BaseTestCase;

use Hamcrest\MatcherAssert as ha;
use Hamcrest\Matchers as hm;

use Pimple\Container;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

use UCD\Application\Console\Command\RepositoryTransferCommand;

use UCD\Application\Container\ConfigurationProvider;
use UCD\Application\Container\ServiceProvider;
use VirtualFileSystem\FileSystem;

class RepositoryTransferCommandTest extends BaseTestCase
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
     * @var string
     */
    protected $dbPath;

    protected function setUp()
    {
        $this->fs = new FileSystem();
        $this->dbPath = $this->fs->path('/db');

        $ucdXmlPath = $this->fs->path('/ucd.xml');

        mkdir($this->dbPath);
        file_put_contents($ucdXmlPath, self::FILE_CONTENT);

        $this->container[ConfigurationProvider::CONFIG_KEY_DB_PATH] = $this->dbPath;
        $this->container[ConfigurationProvider::CONFIG_KEY_XML_PATH] = $ucdXmlPath;

        $application = new Application();
        $application->add(new RepositoryTransferCommand($this->container));
        $command = $application->get(RepositoryTransferCommand::COMMAND_NAME);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @test
     */
    public function it_can_generate_a_file_database()
    {
        $this->commandTester->execute([
            'command' => RepositoryTransferCommand::COMMAND_NAME,
            'from' => 'xml',
            'to' => 'php'
        ]);

        $output = $this->commandTester->getDisplay();
        $statusCode = $this->commandTester->getStatusCode();

        ha::assertThat('output', $output, hm::containsString('Database Generated'));
        ha::assertThat('status code', $statusCode, hm::is(hm::identicalTo(0)));

        $files = $this->getDbFiles();

        ha::assertThat('files', $files, hm::is(hm::arrayWithSize(1)));
        ha::assertThat('file name', $files[0]->getBasename(), hm::is(hm::identicalTo('00000000-01114111!0001.php')));
        ha::assertThat('file size', $files[0]->getSize(), hm::is(hm::greaterThan(0)));

        $data = require $files[0]->getPathname();

        ha::assertThat('dumped data', $data, hm::is(hm::arrayWithSize(1)));
        ha::assertThat('dumped data', $data, hm::hasKeyInArray(0));
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