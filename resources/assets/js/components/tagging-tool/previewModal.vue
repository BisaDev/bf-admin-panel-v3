<template>
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img class="modal-img" :src="modalImageUrl" alt="modal-img" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            modalImageUrl: String
        }
    }
</script>