

self.addEventListener("install", (event) => {
    console.log("Service Worker terpasang...");
    self.skipWaiting();
});


self.addEventListener("activate", (event) => {
    console.log("Service Worker aktif.");
});


self.addEventListener("push", function (event) {
    console.log("Push diterima:", event.data ? event.data.text() : "Data kosong");

    let data = {};

    try {
        data = event.data.json();
    } catch (e) {
        data = {
            title: "Pemberitahuan Keamanan",
            body: event.data.text(),
            url: "/"
        };
    }

    const notifikasiOpsi = {
        body: data.body || "Ada informasi terbaru dari sistem keamanan kampus.",
        icon: "/assets/icon-notif.png",
        badge: "/assets/badge.png",
        vibrate: [200, 100, 200],
        data: {
            url: data.url || "/"
        }
    };

    event.waitUntil(
        self.registration.showNotification(
            data.title || "Pemberitahuan",
            notifikasiOpsi
        )
    );
});


self.addEventListener("notificationclick", function (event) {
    event.notification.close();

    let tujuan = event.notification.data.url;

    event.waitUntil(
        clients.matchAll({ type: "window", includeUncontrolled: true }).then(function (jendela) {
            
            for (let i = 0; i < jendela.length; i++) {
                if (jendela[i].url.includes(tujuan)) {
                    return jendela[i].focus();
                }
            }

            
            return clients.openWindow(tujuan);
        })
    );
});
