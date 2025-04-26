export default {
  methods: {
    setPhoneNumber(phoneNumber, phoneNumberData) {
      this.user.phone_number = phoneNumberData.number;
    },
  },
};
