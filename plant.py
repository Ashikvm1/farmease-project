import tensorflow as tf
import numpy as np
from tensorflow.keras.preprocessing import image
import os

# Load model
model = tf.keras.models.load_model("plant_disease_model.h5")

# Get class names from the dataset
train_dir = "dataset/train"
class_names = sorted(os.listdir(train_dir))  # Auto-load class names

# Load and preprocess image
img_path = r"C:\xampp\htdocs\FarmEase\images\test_image.jpg"  # Update with actual image
if not os.path.exists(img_path):
    print(f"Error: File '{img_path}' not found.")
else:
    img = image.load_img(img_path, target_size=(128, 128))  # Same as training size
    img_array = image.img_to_array(img) / 255.0  # Normalize
    img_array = np.expand_dims(img_array, axis=0)  # Add batch dimension

    # Predict
    predictions = model.predict(img_array)
    predicted_class_index = np.argmax(predictions)  # Get class index

    # Get class name
    predicted_class_name = class_names[predicted_class_index]

    print(f"Predicted Class: {predicted_class_name} (Index {predicted_class_index})")
