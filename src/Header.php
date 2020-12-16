<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

/*
Copyright (c) 2001-2008, Taku Kudo
Copyright (c) 2004-2008, Nippon Telegraph and Telephone Corporation
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are
permitted provided that the following conditions are met:

 * Redistributions of source code must retain the above
   copyright notice, this list of conditions and the
   following disclaimer.

 * Redistributions in binary form must reproduce the above
   copyright notice, this list of conditions and the
   following disclaimer in the documentation and/or other
   materials provided with the distribution.

 * Neither the name of the Nippon Telegraph and Telegraph Corporation
   nor the names of its contributors may be used to endorse or
   promote products derived from this software without specific
   prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED
WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A
PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR
TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

class Header
{
    public static function get(): string
    {
        /**
         * @license BSD-3-Clause
         * @copyright Copyright (c) 2001-2008, Taku Kudo
         * @copyright Copyright (c) 2004-2008, Nippon Telegraph and Telephone Corporation
         */
        return <<<'END'
            struct mecab_dictionary_info_t {
                const char *filename;
                const char *charset;
                unsigned int size;
                int type;
                unsigned int lsize;
                unsigned int rsize;
                unsigned short version;
                struct mecab_dictionary_info_t *next;
            };

            struct mecab_path_t {
                struct mecab_node_t *rnode;
                struct mecab_path_t *rnext;
                struct mecab_node_t *lnode;
                struct mecab_path_t *lnext;
                int cost;
                float prob;
            };

            struct mecab_node_t {
                struct mecab_node_t *prev;
                struct mecab_node_t *next;
                struct mecab_node_t *enext;
                struct mecab_node_t *bnext;
                struct mecab_path_t *rpath;
                struct mecab_path_t *lpath;
                const char *surface;
                const char *feature;
                unsigned int id;
                unsigned short length;
                unsigned short rlength;
                unsigned short rcAttr;
                unsigned short lcAttr;
                unsigned short posid;
                unsigned char char_type;
                unsigned char stat;
                unsigned char isbest;
                float alpha;
                float beta;
                float prob;
                short wcost;
                long cost;
            };

            typedef struct mecab_t mecab_t;
            typedef struct mecab_model_t mecab_model_t;
            typedef struct mecab_lattice_t mecab_lattice_t;
            typedef struct mecab_dictionary_info_t mecab_dictionary_info_t;
            typedef struct mecab_node_t mecab_node_t;
            typedef struct mecab_path_t mecab_path_t;

            mecab_t *mecab_new(int argc, char **argv);
            mecab_t *mecab_new2(const char *arg);
            const char *mecab_version();
            const char *mecab_strerror(mecab_t *mecab);
            void mecab_destroy(mecab_t *mecab);
            //deprecated//int mecab_get_partial(mecab_t *mecab);
            //deprecated//void mecab_set_partial(mecab_t *mecab, int partial);
            //deprecated//float mecab_get_theta(mecab_t *mecab);
            //deprecated//void mecab_set_theta(mecab_t *mecab, float theta);
            //deprecated//int mecab_get_lattice_level(mecab_t *mecab);
            //deprecated//void mecab_set_lattice_level(mecab_t *mecab, int level);
            //deprecated//int mecab_get_all_morphs(mecab_t *mecab);
            //deprecated//void mecab_set_all_morphs(mecab_t *mecab, int all_morphs);
            int mecab_parse_lattice(mecab_t *mecab, mecab_lattice_t *lattice);
            //unused//const char *mecab_sparse_tostr(mecab_t *mecab, const char *str);
            const char *mecab_sparse_tostr2(mecab_t *mecab, const char *str, size_t len);
            //unused//char *mecab_sparse_tostr3(mecab_t *mecab, const char *str, size_t len, char *ostr, size_t olen);
            //unused//const mecab_node_t *mecab_sparse_tonode(mecab_t *mecab, const char*);
            const mecab_node_t *mecab_sparse_tonode2(mecab_t *mecab, const char*, size_t);
            //deprecated//const char *mecab_nbest_sparse_tostr(mecab_t *mecab, size_t N, const char *str);
            //deprecated//const char *mecab_nbest_sparse_tostr2(mecab_t *mecab, size_t N, const char *str, size_t len);
            //deprecated//char *mecab_nbest_sparse_tostr3(mecab_t *mecab, size_t N, const char *str, size_t len, char *ostr, size_t olen);
            //deprecated//int mecab_nbest_init(mecab_t *mecab, const char *str);
            //deprecated//int mecab_nbest_init2(mecab_t *mecab, const char *str, size_t len);
            //deprecated//const char *mecab_nbest_next_tostr(mecab_t *mecab);
            //deprecated//char *mecab_nbest_next_tostr2(mecab_t *mecab, char *ostr, size_t olen);
            //deprecated//const mecab_node_t *mecab_nbest_next_tonode(mecab_t *mecab);
            //deprecated//const char *mecab_format_node(mecab_t *mecab, const mecab_node_t *node);

            const mecab_dictionary_info_t *mecab_dictionary_info(mecab_t *mecab);

            mecab_lattice_t *mecab_lattice_new();
            void mecab_lattice_destroy(mecab_lattice_t *lattice);
            void mecab_lattice_clear(mecab_lattice_t *lattice);
            int mecab_lattice_is_available(mecab_lattice_t *lattice);
            mecab_node_t *mecab_lattice_get_bos_node(mecab_lattice_t *lattice);
            mecab_node_t *mecab_lattice_get_eos_node(mecab_lattice_t *lattice);
            mecab_node_t **mecab_lattice_get_all_begin_nodes(mecab_lattice_t *lattice);
            mecab_node_t **mecab_lattice_get_all_end_nodes(mecab_lattice_t *lattice);
            //unused//mecab_node_t *mecab_lattice_get_begin_nodes(mecab_lattice_t *lattice, size_t pos);
            //unused//mecab_node_t *mecab_lattice_get_end_nodes(mecab_lattice_t *lattice, size_t pos);
            const void *mecab_lattice_get_sentence(mecab_lattice_t *lattice); // changed from char to support NIL, don't know if that matters though
            //unused//void mecab_lattice_set_sentence(mecab_lattice_t *lattice, const char *sentence);
            void mecab_lattice_set_sentence2(mecab_lattice_t *lattice, const char *sentence, size_t len);
            size_t mecab_lattice_get_size(mecab_lattice_t *lattice);
            double mecab_lattice_get_z(mecab_lattice_t *lattice);
            void mecab_lattice_set_z(mecab_lattice_t *lattice, double Z);
            double mecab_lattice_get_theta(mecab_lattice_t *lattice);
            void mecab_lattice_set_theta(mecab_lattice_t *lattice, double theta);
            int mecab_lattice_next(mecab_lattice_t *lattice);
            int mecab_lattice_get_request_type(mecab_lattice_t *lattice);
            int mecab_lattice_has_request_type(mecab_lattice_t *lattice, int request_type);
            void mecab_lattice_set_request_type(mecab_lattice_t *lattice, int request_type);
            void mecab_lattice_add_request_type(mecab_lattice_t *lattice, int request_type);
            void mecab_lattice_remove_request_type(mecab_lattice_t *lattice, int request_type);
            mecab_node_t *mecab_lattice_new_node(mecab_lattice_t *lattice);
            const char *mecab_lattice_tostr(mecab_lattice_t *lattice);
            //unused//const char *mecab_lattice_tostr2(mecab_lattice_t *lattice, char *buf, size_t size);
            const char *mecab_lattice_nbest_tostr(mecab_lattice_t *lattice, size_t N);
            //unused//const char *mecab_lattice_nbest_tostr2(mecab_lattice_t *lattice, size_t N, char *buf, size_t size);
            int mecab_lattice_has_constraint(mecab_lattice_t *lattice);
            int mecab_lattice_get_boundary_constraint(mecab_lattice_t *lattice, size_t pos);
            const char *mecab_lattice_get_feature_constraint(mecab_lattice_t *lattice, size_t pos);
            void mecab_lattice_set_boundary_constraint(mecab_lattice_t *lattice, size_t pos, int boundary_type);
            void mecab_lattice_set_feature_constraint(mecab_lattice_t *lattice, size_t begin_pos, size_t end_pos, const char *feature);
            const char *mecab_lattice_strerror(mecab_lattice_t *lattice);

            mecab_model_t *mecab_model_new(int argc, char **argv);
            //unused//mecab_model_t *mecab_model_new2(const char *arg);
            void mecab_model_destroy(mecab_model_t *model);
            mecab_t *mecab_model_new_tagger(mecab_model_t *model);
            mecab_lattice_t *mecab_model_new_lattice(mecab_model_t *model);
            int mecab_model_swap(mecab_model_t *model, mecab_model_t *new_model);
            const mecab_dictionary_info_t *mecab_model_dictionary_info(mecab_model_t *model);
            int mecab_model_transition_cost(mecab_model_t *model, unsigned short rcAttr, unsigned short lcAttr);
            mecab_node_t *mecab_model_lookup(mecab_model_t *model, const char *begin, const char *end, mecab_lattice_t *lattice);

            int mecab_do(int argc, char **argv);
            int mecab_dict_index(int argc, char **argv);
            int mecab_dict_gen(int argc, char **argv);
            int mecab_cost_train(int argc, char **argv);
            int mecab_system_eval(int argc, char **argv);
            int mecab_test_gen(int argc, char **argv);

            END;
    }
}
