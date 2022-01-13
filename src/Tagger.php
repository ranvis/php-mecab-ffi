<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class Tagger
{
    use TokenIssuerTrait;

    private Env $env;
    private FFI\CData $tagger;

    public function __construct(
        Env|Model $origin,
        array|string $args = [],
    ) {
        if ($origin instanceof Env) {
            $this->env = $origin;
            $lib = $this->env->lib();
            if (!is_array($args)) {
                // N.B. empty string != no args
                $tagger = $lib->mecab_new2($args);
            } else {
                array_unshift($args, 'mecab');  // A program name as we have in argv[0].
                $gc = [];
                $argsList = FFI::new('char *[' . count($args) . ']');
                $index = 0;
                foreach ($args as $arg) {
                    $argCharP = FfiUtil::newCString((string)$arg);
                    $gc[] = $argCharP;
                    $argsList[$index++] = $argCharP->value;
                }
                $tagger = $lib->mecab_new(count($args), $argsList);
                unset($gc);
            }
        } else {
            if ($args !== []) {
                throw new \InvalidArgumentException('Args cannot be specified with Model');
            }
            $this->env = $origin->getEnv();
            $tagger = $this->env->lib()->mecab_model_new_tagger($origin);
        }
        if ($tagger === null) {
            $message = $this->env->lib()->mecab_strerror(null);
            if ($message === '') {  // https://github.com/taku910/mecab/issues/57
                $message = 'Could not instantiate Tagger';
            }
            throw new \InvalidArgumentException($message);
        }
        $this->tagger = $tagger;
    }

    public function __destruct()
    {
        $this->freeToken();
        $this->env->lib()->mecab_destroy($this->tagger);
    }

    public function getLastError(): string
    {
        return $this->env->lib()->mecab_strerror($this->tagger);
    }

    public function parseLattice(Lattice $lattice): bool
    {
        return (bool)$this->env->lib()->mecab_parse_lattice($this->tagger, $lattice->getObject());
    }

    public function parse(string $str): string
    {
        $strCharP = FfiUtil::newBuffer($str);
        $result = $this->env->lib()->mecab_sparse_tostr2($this->tagger, $strCharP->value, strlen($str));
        return $result;
    }

    public function parseToNode(string $str): Node
    {
        $token = $this->changeToken();
        $strCharP = FfiUtil::newBuffer($str); // node.surface references this buffer
        $token->addChild($strCharP);
        return new Node($this->env->lib()->mecab_sparse_tonode2($this->tagger, $strCharP->value, strlen($str)), $token->wrap());
    }

    public function getDictionaryInfo(): array
    {
        $list = [];
        $info = $this->env->lib()->mecab_dictionary_info($this->tagger);
        while ($info) {
            $list[] = new DictionaryInfo($info);
            $info = $info->next;
        }
        return $list;
    }
}
