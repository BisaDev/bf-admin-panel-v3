var tagRepository = {
    mounted() {
        let url = $('#tags').data('tag_repository');
        
        $('#tags').tagsinput({
            tagClass: 'label label-primary',
            typeaheadjs: 
            [{
                //options
            },
            {
                async: true,
                source: function (query, processSync, processAsync) {
                    return axios.post(url, {query: query}).then(function(response){ return processAsync(response.data); });
                }
            }]
        });
    }
}
export default tagRepository;