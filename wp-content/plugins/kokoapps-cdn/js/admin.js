(function ($) {
  $(function () {
    console.log('start cdn;');
    if (typeof firebaseConfig.debug !== "undefined" && firebaseConfig.debug) {
      console.log(firebaseConfig);
    }

    $.urlParam = function (name) {
      var results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
        window.location.href
      );
      return results ? results[1] || 0 : 0;
    };

    var mediaUploader;
    var mediaUploaderMp3 = false;
    var mediaAdd;
    var audoReady;
    var authLoading = false;
    var postid = $.urlParam("post");
    var postMp3Url = "";

    if (!postid) {
      $('#podcast div.inside').html('כדי לטעון קובץ שמע, יש לשמור את הפוסט ולרענן את הדף!');
      return;
    }
    
    firebase.initializeApp(firebaseConfig);

    firebase
      .auth()
      .signInAnonymously()
      .catch(function (error) {
        var errorCode = error.code;
        var errorMessage = error.message;
        console.log(errorCode);
      });

    firebase.auth().onAuthStateChanged(function (user) {
      console.log('firebase auth...');
      if (user) {
        // User is signed in.
        var storage = firebase.storage();
        var pathReference = storage.ref(
          "/" + firebaseConfig.cdn_folder + "/" + postid + ".mp3"
        );
        res = pathReference
          .getDownloadURL()
          .then(ref => {
            return ref;
          })
          .catch(err => {
            var err = JSON.parse(err.serverResponse);
            if (err.error.code === 404) {
              postMp3Url = "";
              $("#podcast_cdn").hide();
            }
          });
        res.then(url => {
          authLoading = true;
          postMp3Url = url;
        });
        //authLoading = true;
      } else {
        authLoading = false;
      }
    });

    $("#_biu_podcast_cdn").hide();
    $(loader()).appendTo($("#_biu_podcast_cdn").parent());


    audoReady = setInterval(function () {
      console.log('wait podcast_cdn..');
      if (document.getElementById("podcast_cdn") !== null && authLoading === true) {
        clearInterval(audoReady);
        var audio = document.getElementById("podcast_cdn");
        audio.onloadedmetadata  = function () {
          setDurtion();
        };
      }
    }, 5);

    mediaAdd = setInterval(function () {
      console.log('looking _biu_podcast_cdn..');

      if (jQuery("#_biu_podcast_cdn") && authLoading === true) {
        clearInterval(mediaAdd);

        //hide input because if all items will be idden meta box is hidden :)
        $("#_biu_podcast_cdn").hide();
        $("#loader").hide();

        //add audio html5 to meta box
        $("#_biu_podcast_cdn").attr("value", postMp3Url);
        $(addButton() + addAudio(postMp3Url)).appendTo(
          $("#_biu_podcast_cdn").parent()
        );



        $("#podcast_cdn_upload").on("click", function (e) {
          e.preventDefault();
          if (mediaUploader) {
            mediaUploader.open();
            return;
          }

          const { __, _x, _n, _nx } = wp.i18n;

          mediaUploader = wp.media.frames.file_frame = wp.media({
            title: wp.a11y.speak(__("Upload New Media")),
            button: { text: __("Add New") },
            multiple: false,
            library: {
              order: "DESC",
              orderby: "date",
              type: "audio",
              search: null,
              uploadedTo: wp.media.view.settings.post.id
            }
          });

          mediaUploader.on("ready", function () {
            console.log("Upload ready!");
            mediaUploaderMp3 = true;
          });

          mediaUploader.on("attach", function () {
            console.log("Upload attach!");
          });

          mediaUploader.on("select", function () {
            attachment = mediaUploader
              .state()
              .get("selection")
              .first()
              .toJSON();
            if (attachment.id) {
              $("#_biu_podcast_cdn").val(attachment.url);
              $("#podcast_cdn").attr("src", attachment.url);
              $("#podcast_cdn").load();
              setDurtion();
            }
          });
          mediaUploader.open();
        });

        if (
          typeof wp.Uploader !== "undefined" &&
          typeof wp.Uploader.queue !== "undefined"
        ) {
          wp.Uploader.queue.on("add", function (file) {
            console.log("Upload add!");
            console.log(wp.media.view.settings.post.id);

            var callback = function () {
              console.log("Finish!");
            };

            if (mediaUploaderMp3 === true) {
              $("#__wp-uploader-id-" + (wp.Uploader.uuid - 1)).hide();
              jQuery(".editor-post-publish-button").attr("disabled", true);
              $(".rwmb-meta-box").hide();
              //var publishText = jQuery(".editor-post-publish-button").text();
              var callback = function (downloadURL) {
                console.log("File available at", downloadURL);
                jQuery(".editor-post-publish-button").attr("disabled", false);
                //jQuery(".editor-post-publish-button").text(publishText);
                jQuery("#podcast_cdn").attr("src", downloadURL);
                jQuery("#podcast_cdn").load();
                jQuery(".rwmb-meta-box").show();
                jQuery("#loader").hide();
              };
            }

            uploadFile(
              file,
              firebase
                .app()
                .storage()
                .ref(),
              new Date().getTime(),
              0,
              callback
            );
          });

          wp.Uploader.queue.on("reset", function () {
            console.log("Upload reset!");
            if (mediaUploaderMp3 === true) {
              //$("#__wp-uploader-id-" + (wp.Uploader.uuid - 1)).hide();
            }
          });

          wp.Uploader.queue.on("select", function () {
            console.log("Upload update!");
          });
        }
      }
    }, 50);

    //###################################################
    function uploadFile(fileObj, storage, startTime, count, callback) {
      var file = fileObj.attributes.file.getNative();
      var reader = new FileReader();
      reader.onload = function (event) {
        $.LoadingOverlay("show", {
          image:
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><circle r="80" cx="500" cy="90"/><circle r="80" cx="500" cy="910"/><circle r="80" cx="90" cy="500"/><circle r="80" cx="910" cy="500"/><circle r="80" cx="212" cy="212"/><circle r="80" cx="788" cy="212"/><circle r="80" cx="212" cy="788"/><circle r="80" cx="788" cy="788"/></svg>',
          background: "rgba(0, 0, 0, 0.5)",
          imageAnimation: "rotate_right 2s",
          imageColor: "#6cbdd0",
          text: "Upload to cloud ...."
        });
        var count = 0;

        var filename = fileObj.attributes.filename,
          extension = filename.substr(filename.lastIndexOf(".") + 1),
          binary = event.target.result,
          pathName =
            "/" +
            firebaseConfig.cdn_folder +
            "/" +
            wp.media.view.settings.post.id +
            "." +
            extension,
          image = storage.child(pathName),
          metadata = {
            contentType: file.type
          };
        var uploadTask = image.put(new Blob([binary]), metadata);
        uploadTask.on("state_changed", function (snapshot) {
          var progress =
            (snapshot.bytesTransferred / snapshot.totalBytes) * 100;
          console.log("Upload is " + progress + "% done");
          switch (snapshot.state) {
            case firebase.storage.TaskState.PAUSED: // or 'paused'
              console.log("Upload is paused");
              break;
            case firebase.storage.TaskState.RUNNING: // or 'running'
              console.log("Upload is running");
              break;
          }

          $.LoadingOverlay(
            "text",
            "Upload to cloud: " +
            snapshot.state +
            "... " +
            Math.round(progress) +
            "% done"
          );
          $.LoadingOverlay("progress", Math.round(progress));
        });
        uploadTask
          .then(function (snapshot) {
            // Handle successful uploads on complete
            // For instance, get the download URL: https://firebasestorage.googleapis.com/...
            snapshot.ref.getDownloadURL().then(function (downloadURL) {
              callback(downloadURL);
              $.LoadingOverlay("hide");
            });
            var endTime = new Date().getTime();
            performances[count] = endTime - startTime;
            if (count < 1) {
              function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
              }
              sleep(1000).then(() => {
                uploadFile(storage, file, new Date().getTime(), count + 1);
              });
            } else {
              var sum = performances.reduce((a, b) => a + b, 0);
            }
          })
          .catch(function (error) {
            console.log(error);
          });
      };
      reader.readAsArrayBuffer(file);
    }

    function addButton() {
      return (
        '<div id="podcast_cdn_upload" class="rwmb-media-add" style="line-height: 69px;display:inline-block;padding-left: 10px;padding-right: 10px;">' +
        '<a class="button">+ Add Media</a>' +
        "</div>"
      );
    }

    function loader() {
      return '<div id="loader" class="loader"></div><style>.loader {border: 16px solid #f3f3f3;border-top: 16px solid #3498db;border-radius: 50%;width: 120px;height: 120px;animation: spin 2s linear infinite;}@keyframes spin {0% { transform: rotate(0deg); }100% { transform: rotate(360deg); }}</style>';
    }

    function addAudio(url) {
      return (
        '<audio preload="auto" controls id="podcast_cdn">' +
        '<source src="' +
        url +
        '" type="audio/mpeg">' +
        "</audio>"
      );
    }

    function setDurtion() {
      try {
        var vid = document.getElementById("podcast_cdn");
        $("#_biu_podcast_cdn_duration").val(vid.duration);
        console.log('_biu_podcast_cdn_duration: ' + vid.duration);
      } catch (err) {
        console.log(err);
      }
    }
  });
})(jQuery);
