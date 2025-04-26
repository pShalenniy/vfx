(() => {
  const setActiveLink = (selector, callback = () => {}) => {
    document.querySelectorAll(selector).forEach(($link) => {
      if ($link.href === window.location.href) {
        callback();

        $link.classList.add('active');
      }
    });
  };

  window.addEventListener('DOMContentLoaded', () => {
    // Set active link in top menu.
    setActiveLink('#top-navbar-menu > li.nav-item > a.nav-link');

    const accountSettingsSubMenuLinks = [
      'user.edit',
      'user.shortlists',
      'user.show',
      'user.subscription',
    ];

    // Set active link in account settings menu.
    accountSettingsSubMenuLinks.forEach((routeName) => {
      if (route().current(routeName)) {
        const $accountSettingsLink = document.getElementById('account-settings-link');

        $accountSettingsLink.classList.add('active');
      }
    });
  });
})();
