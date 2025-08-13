const express = require("express");
const cors = require("cors");
const { GoogleGenAI } = require("@google/genai");
require("dotenv").config();

const app = express();
app.use(cors());
app.use(express.json());

const ai = new GoogleGenAI({
  apiKey: process.env.GOOGLE_API_KEY,
});

app.post("/gemini", async (req, res) => {
  const { prompt } = req.body;

  if (!prompt) {
    return res.status(400).json({ error: "Missing prompt" });
  }

  try {
    const response = await ai.models.generateContent({
      model: "gemini-2.5-flash-lite",
      contents: prompt,
      config: {
        thinkingConfig: {
          thinkingBudget: 0,
        },
      },
    });

    res.json({ text: response.text });
  } catch (err) {
    console.error("Gemini error:", err);
    res.status(500).json({ error: "Failed to call Gemini API" });
  }
});

const PORT = 3000;
app.listen(PORT, () => {
  console.log(`✅ Node.js Gemini server running at http://localhost:${PORT}`);
});
