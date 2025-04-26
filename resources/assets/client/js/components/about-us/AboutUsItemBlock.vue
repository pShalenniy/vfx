<template>
  <div class="about-us-item-block">
    <div class="row align-items-center">
      <div class="col-lg-3 col-sm-4 mb-4 mb-sm-0">
        <div class="about-us-item">
          <img
            :src="image"
            alt=""
            height="100"
            width="100"
          />
          <div class="about-us-item__title">
            <p class="text-primary">
              <b>{{ fullName }}</b>
            </p>
            <p class="text-primary">
              <b>{{ position }}</b>
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-5 col-sm-5">
        <div class="pl-md-4">
          <h4 class="h4">
            <b>{{ fullName }}</b>
          </h4>
          <div class="text-indent my-4 text-medium">
            {{ text }}
          </div>
          <div>
            <a :href="'mailto:'+`${email}`">
              <p class="text-medium">
                <b>{{ linkTitle }}</b>
              </p>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    image: {
      type: String,
      required: true,
    },
    fullName: {
      type: String,
      required: true,
    },
    position: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      required: true,
    },
    email: {
      type: String,
      required: true,
    },
    linkTitle: {
      type: String,
      required: true,
    },
  },
};
</script>
