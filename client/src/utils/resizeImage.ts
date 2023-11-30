const resizeImage = (
  input: {
    file: File | Blob;
    width?: number;
    height?: number;
  },
  callback: (resultBlob: Blob) => void
) => {
  const { file, height = 500, width = 500 } = input;

  const img: HTMLImageElement = new window.Image();
  img.src = URL.createObjectURL(file);
  img.onload = async function () {
    const canvas = document.createElement("canvas");
    const context = canvas.getContext("2d");
    if (!context) return;

    canvas.width = width;
    canvas.height = height;
    context.drawImage(img, 0, 0, canvas.width, canvas.height);

    const response = await fetch(canvas.toDataURL());
    const imageBlob = await response.blob();
    callback(imageBlob);
  };
};

export default resizeImage;
