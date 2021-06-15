<template>
    <span v-if="!isFailed" class="col-span-2 grid grid-flow-rows relative cursor-pointer auto-rows-max py-3 inline-flex items-center px-4 bg-gray-100 border-dashed border-4 font-semibold justify-center text-md text-gray-500 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" >
        <input type="file" :name="uploadFieldName" :disabled="isSaving" @change="filesChange($event.target.name, $event.target.files);" class="opacity-0 absolute top-0 left-0 w-full h-full cursor-pointer">
        <unicon fill="currentColor" class="p-2 my-5 mx-auto" height="45%" width="45%" name="file-upload-alt"></unicon>
        <span v-if="isInitial" class="text-center">Dokument hochladen</span>
        <span v-if="isSaving">Lade Dokument hoch...</span>    </span>
    <span v-else class="col-span-2">
        <!--FAILED-->
        <span v-if="isFailed" class="col-span-2 grid grid-flow-rows relative auto-rows-max py-3 inline-flex items-center px-4 bg-gray-100 border-dashed border-4 font-semibold justify-center text-md text-gray-500 uppercase tracking-widest hover:bg-gray-200 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" >
            <p>Upload fehlgeschlagen.</p>
            <a href="javascript:void(0)" @click="reset()">Erneut versuchen</a>
        </span>
        <span v-if="isFailed">
            <pre>{{ uploadError }}</pre>
        </span>
    </span>
</template>

<script>

const STATUS_INITIAL = 0, STATUS_SAVING = 1, STATUS_FAILED = 2;

export default {
    props: {
        id: Number,
        documents: Object,
    },
    data() {
        return {
            uploadError: null,
            currentStatus: null,
            uploadFieldName: 'document',
        }
    },
    computed: {
        isInitial() {
            return this.currentStatus === STATUS_INITIAL;
        },
        isSaving() {
            return this.currentStatus === STATUS_SAVING;
        },
        isFailed() {
            return this.currentStatus === STATUS_FAILED;
        },
    },
    methods: {
        reset() {
            // reset form to initial state
            this.currentStatus = STATUS_INITIAL;
            this.uploadError = null;
        },
        save(formData) {
            // upload data to the server
            this.currentStatus = STATUS_SAVING;
            axios.post(this.route('documents.store', this.id), formData)
                .then(response => {
                    this.documents.push(response.data);
                    this.reset();
                })
                .catch(err => {
                    this.uploadError = err.response;
                    this.currentStatus = STATUS_FAILED;
                });
        },
        filesChange(fieldName, fileList) {
            // handle file changes
            const formData = new FormData();

            if (!fileList.length) return;

            // append the files to FormData
            Array
                .from(Array(fileList.length).keys())
                .map(x => {
                    formData.append(fieldName, fileList[x], fileList[x].name);
                });

            // save it
            this.save(formData);
        },
    },
    mounted() {
        this.reset();
    },
}
</script>