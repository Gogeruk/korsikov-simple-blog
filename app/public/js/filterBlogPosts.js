document.addEventListener('DOMContentLoaded', function () {
    const tagList = document.getElementById('tag-list');
    const postList = document.getElementById('post-list');

    tagList.addEventListener('click', function (event) {
        if (event.target.tagName === 'A') {
            event.preventDefault();
            const tag = event.target.getAttribute('data-tag');
            fetchPosts(tag);
        }
    });

    function fetchPosts(tag) {
        const url = new URL(fetchBlogPostsUrl, window.location.origin);
        if (tag) {
            url.searchParams.append('tag', tag);
        }

        fetch(url.toString())
            .then(response => response.json())
            .then(posts => {
                postList.innerHTML = '';
                if (posts.length === 0) {
                    postList.innerHTML = '<li>No posts found.</li>';
                    return;
                }

                posts.forEach(post => {
                    post.slug = post.slug.replace(/^blog\//, '');

                    const postElement = document.createElement('li');
                    postElement.innerHTML = `
                        <a href="/blog/${post.slug}">${post.title}</a>
                    `;
                    postList.appendChild(postElement);
                });
            });
    }
});