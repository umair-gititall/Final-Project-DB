const express = require("express");
const cors = require("cors");
const { GoogleGenAI } = require("@google/genai");
const emailCheck = require("email-check");
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

app.post("/validate", async (req, res) => {
  const { email } = req.body;
  if (!email) {
    return res
      .status(400)
      .json({ valid: false, message: "Email required~! 😭" });
  }

  try {
    const exists = await emailCheck(email);
    res.json({ valid: exists });
  } catch (err) {
    console.error(err);
    res.status(500).json({ valid: false, message: "Validation error 😣" });
  }
});

const PORT = 3000;
app.listen(PORT, () => {
  console.log(`✅ Combined server running at http://localhost:${PORT}`);
});
