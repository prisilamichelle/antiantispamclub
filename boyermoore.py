import sys
topic = sys.argv[1]
key = sys.argv[2]

#Last occurence function, return index i such that p[i]==x else return -1
def lastOccur(p):
    last = []
    for i in range(0,128,1):
        last.append(-1)
    for j in range(len(p)):
        last[ord(p[j])] = j
    return last

#The Boyer Moore Algorithm, return the index, if not found return -1
def boyerMoore(t, p):
    last = lastOccur(p)
    n = len(t)
    m = len(p)

    i = j = m-1
    
    if (m>n):
        return -1
    
    if (t[i]==p[j]):
        if (j==0):
            return i
        else:
            i-=1
            j-=1
    else:
        if (ord(t[i])>127):
            i = i + m -j
            j = m - 1
        else:
            loc = last[ord(t[i])]
            i = i + m - min(j, 1+loc)
            j = m - 1

    while (i<n):
        if (t[i]==p[j]):
            if (j==0):
                return i
            else:
                i-=1
                j-=1
        else:
            if (ord(t[i])>127):
                i = i + m -j
                j = m - 1
            else:
                loc = last[ord(t[i])]
                i = i + m - min(j, 1+loc)
                j = m - 1
    
    return -1

#Twitter API + Boyer-Moore
def spamDetect(topic,keyword):
    #Setup Twitter API
    from twython import Twython
    APP_KEY = 'pVqijAxfbDRTWBadqEHs43Ozm'
    APP_SECRET = '5n0b7DDeCYCqeiHxk2vDNR6gurMcj3mEBAJQ6xIlStShY3R0DR'
    OAUTH_TOKEN = '985071710100598784-NTA548fEqcop86mOBV2bbHUOaDfz3X4'
    OAUTH_TOKEN_SECRET = 'uByR9KjsKdqLL5c1rmcUCmC3G9MNesoWwEuZ1Zf5sskLv'

    twitter = Twython(APP_KEY, APP_SECRET, OAUTH_TOKEN, OAUTH_TOKEN_SECRET)

    #From search
    result = twitter.search(q=topic, count=30, lang='id')
    all_tweets = result['statuses']
    num = 1
    for tweet in all_tweets:
        print("<br>")
        print(num,end="")
        print(".")
        print(tweet['created_at'])
        print("[")
        print(tweet['user']['screen_name'])
        print("]")
        idx = boyerMoore(str(tweet['text']), keyword)
        tweet['text'] = Twython.html_for_tweet(tweet)
        print(tweet['text'].encode("utf-8"))
        if (idx!=-1):
            print('>>>SPAM<<<')
        num+=1

spamDetect(topic,key)