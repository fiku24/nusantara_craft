<?php

namespace ICB;

class Posts {

    static function query( $attributes ) {
        extract( $attributes );
        $taxonomyRelation = $taxonomyRelation ?? 'AND';

        $selectedCategories = $selectedCategories ?? [];
        $selectedTags       = $selectedTags ?? [];
        $selectedTaxonomies = $selectedTaxonomies ?? [];

        $termsQuery = [ 'relation' => $taxonomyRelation ];

        if ( 'post' === $postType && count( $selectedCategories ) ) {
            $termsQuery[] = [
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => $selectedCategories,
            ];
        }

        if ( 'post' === $postType && count( $selectedTags ) ) {
            $termsQuery[] = [
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $selectedTags,
            ];
        }

        foreach ( $selectedTaxonomies as $taxonomy => $terms ) {
            if ( count( $terms ) ) {
                $termsQuery[] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $terms,
                ];
            }
        }

        $postsInclude = Functions::filterNaN( $postsInclude ?? [] );
        $post__in     = ! empty( $postsInclude ) ? [ 'post__in' => $postsInclude ] : [];
        $post__not_in = Functions::filterNaN( $postsExclude ?? [] );
        $postsAuthors = Functions::filterNaN( $postsAuthors ?? [] );
        $author__in   = ! empty( $postsAuthors ) ? [ 'author__in' => $postsAuthors ] : [];

        $currentPostId = get_the_ID() ?: ( $currentPostId ?? 0 );
        if ( ! empty( $isExcludeCurrent ) && $currentPostId ) {
            $post__not_in[] = $currentPostId;
        }

        if ( ! empty( $isExcludeSticky ) ) {
            $stickyPosts = get_option( 'sticky_posts' );
            if ( ! empty( $stickyPosts ) ) {
                $post__not_in = array_merge( $post__not_in, $stickyPosts );
            }
        }

        $query = array_merge( [
            'post_type'      => $postType,
            'posts_per_page' => $isPostsPerPageAll ? -1 : (int) $postsPerPage,
            'orderby'        => $postsOrderBy,
            'order'          => $postsOrder,
            's'              => $postsSearch ?? '',
            'tax_query'      => count( $termsQuery ) > 1 ? $termsQuery : [],
            'offset'         => $isPostsPerPageAll ? 0 : (int) ( $postsOffset ?? 0 ),
            'post__not_in'   => array_unique( $post__not_in ),
            'has_password'   => false,
            'post_status'    => 'publish',
        ], $post__in, $author__in );

        return $query;
    }

    static function getPosts( $attributes, $pageNumber = 1 ) {
        extract( $attributes );

        $attributes['isPostsPerPageAll'] = isset( $isPostsPerPageAll ) && ( 'true' == $isPostsPerPageAll || 1 == $isPostsPerPageAll );
        $attributes['isExcludeCurrent']  = isset( $isExcludeCurrent ) && ( $isExcludeCurrent == 'true' || 1 == $isExcludeCurrent );

        $postsPerPage = isset( $postsPerPage ) ? (int) $postsPerPage : 6;
        $pageNumber   = (int) $pageNumber;
        $postsOffset  = isset( $postsOffset ) ? (int) $postsOffset : 0;

        $offset = ( $postsPerPage * max( 0, $pageNumber - 1 ) ) + $postsOffset;

        $fImgSize       = $fImgSize ?? 'full';
        $metaDateFormat = $metaDateFormat ?? 'M j, Y';

        $newArgs = wp_parse_args( [ 'offset' => $offset ], self::query( $attributes ) );
        $posts   = Functions::arrangedPosts(
            get_posts( $newArgs ),
            $postType,
            $fImgSize,
            $metaDateFormat
        );

        return $posts;
    }
}
